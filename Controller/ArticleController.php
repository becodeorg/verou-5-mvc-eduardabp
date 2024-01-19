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
            $articles[] = new Article($rawArticle['title'], $rawArticle['description'], $rawArticle['publish_date']);
        }

        return $articles;
    }

    public function show()
    {
        // TODO: this can be used for a detail page
    }
}