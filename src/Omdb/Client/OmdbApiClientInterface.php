<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use App\Omdb\Client\Model\Movie;
use App\Omdb\Client\Model\SearchResult;

interface OmdbApiClientInterface
{
    /**
     * @throws NoResult When the $imdbID was not found.
     */
    public function getByImdbId(string $imdbID): Movie;

    /**
     * @return list<SearchResult>
     *
     * @throws NoResult When the $title returned no result.
     */
    public function searchByTitle(string $title): array;
}
