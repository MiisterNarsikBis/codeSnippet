<?php

class CodeSnippet
{
    private ?int $id;
    private ?string $langage;
    private ?string $tags;
    private ?string $code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLangage(): ?string
    {
        return $this->langage;
    }

    public function setLangage(string $langage): void
    {
        $this->langage = $langage;
    }

    /**
     * @throws JsonException
     */
    public function getTags(): ?array
    {
        return json_decode($this->tags, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public function setTags(?array $tags): void
    {
        $this->tags = json_encode($tags, JSON_THROW_ON_ERROR);
    }

    public function getCode(): ?string
    {
        return base64_decode($this->code);
    }

    public function setCode(string $code): void
    {
        $this->code = base64_encode($code);
    }

    public function getDecodedCode(): ?string
    {
        return base64_decode($this->code);
    }

    /** -- METHODES -- */
    public static function createFromDatabaseRow(array $row): CodeSnippet
    {
        $snippet = new CodeSnippet();
        $snippet->setId($row['id']);
        $snippet->setLangage($row['langage']);
        $snippet->tags = $row['tags']; // Assuming tags are already JSON encoded in the database
        $snippet->code = $row['code64'];
        return $snippet;
    }

    public function isCodeExists(PDO $bdd, string $encodedCode): bool
    {
        $query = $bdd->prepare('SELECT COUNT(*) FROM code WHERE code64 = :encodedCode');
        $query->execute([
            'encodedCode' => $encodedCode
        ]);

        return $query->fetchColumn() > 0;
    }


    public function save(PDO $bdd)
    {
        if ($this->isCodeExists($bdd, $this->code)) {
            return null; // Code already exists, return null or handle as needed
        }

        $query = $bdd->prepare('REPLACE INTO code (langage, tags, code64) VALUES (:langage, :tags, :code64)');
        $query->execute([
            'langage' => $this->langage,
            'tags' => $this->tags,
            'code64' => $this->code
        ]);

        return $bdd->lastInsertId();
    }

    public function search(PDO $bdd, ?string $langage, ?array $tags, $json = false)
    {
        $query = 'SELECT * FROM code WHERE';
        $params = [];

        if ($langage) {
            $query .= ' langage = :langage';
            $params['langage'] = $langage;
        }

        if ($tags) {
            if ($langage) {
                $query .= ' AND';
            }

            $tagPlaceholders = [];
            foreach ($tags as $index => $tag) {
                $tagPlaceholder = ':tag' . $index;
                $tagPlaceholders[] = $tagPlaceholder;
                $params[$tagPlaceholder] = '%"' . $tag . '"%'; // Change this line
            }

            $tagList = implode(' OR ', array_map(fn($ph) => "tags LIKE $ph", $tagPlaceholders));
            $query .= " ($tagList)";
        }

        $statement = $bdd->prepare($query);
        $statement->execute($params);

        if($json === true) {
            $json = [];
            foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $json[] = [
                    'id' => $row['id'],
                    'langage' => $row['langage'],
                    'tags' => json_decode($row['tags'], true, 512, JSON_THROW_ON_ERROR),
                    'code' => base64_decode($row['code64'])
                ];
            }

            return $json;
        }

        $snippets = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $snippets[] = self::createFromDatabaseRow($row);
        }

        return $snippets;
    }
}
