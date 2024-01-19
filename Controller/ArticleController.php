<?php

declare(strict_types = 1);

class ArticleController
{

    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function index()
    {
        // Load all required data
        $articles = $this->getArticles();

        // Load the view
        require 'View/articles/index.php';
    }

    private function getArticles()
    {
    
        try {
            $statement = $this->databaseManager->connection->prepare("SELECT * FROM articles");
            $statement->execute();
            $rawArticles = $statement->fetchAll(PDO::FETCH_ASSOC);
    
            return $rawArticles;
        } catch (PDOException $error) {
            echo $error->getMessage();
            return [];
        }

        $articles = [];
        foreach ($rawArticles as $rawArticle) {
            // We are converting an article from a "dumb" array to a much more flexible class
            $articles[] = new Article($rawArticle['id'], $rawArticle['title'], $rawArticle['description'], $rawArticle['publish_date']);
        }

        return $articles;
    }

    public function show()
    {
        $article = $this->findArticle();
        require 'View/articles/show.php';
    }

    public function findArticle(int $id): array
    {
        try {
            $statement = $this->databaseManager->connection->prepare("SELECT * FROM articles WHERE id = ?");
            $statement->execute([$id]);
            $rawArticle = $statement->fetch(PDO::FETCH_ASSOC);

            return new Article($rawArticle['title'], $rawArticle['description'], $rawArticle['publish_date']);
        } catch (PDOException $error) {
            echo $error->getMessage();
            return null;
        }
    }
}