<?php

declare(strict_types=1);

namespace App\Omdb\Client;

use App\Omdb\Client\Model\Movie;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class ApiClient implements OmdbApiClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $omdbApiClient,
    ) {
    }

    public function getByImdbId(string $imdbID): Movie
    {
        $response = $this->omdbApiClient->request('GET', '/', [
            'query' => [
                'i' => $imdbID,
                'plot' => 'full'
            ]
        ]);

        try {
            /** @var array{Title: string, Year: string, Rated: string, Released: string, Genre: string, Plot: string, Poster: string, imdbID: string, Type: string, Response: string} $movieRaw */
            $movieRaw = $response->toArray(true);
        } catch (Throwable $throwable) {
            throw NoResult::forId($imdbID, $throwable);
        }

        if (array_key_exists('Response', $movieRaw) === true && 'False' === $movieRaw['Response']) {
            throw NoResult::forId($imdbID);
        }

        return new Movie(
            Title: $movieRaw['Title'],
            Year: $movieRaw['Year'],
            Rated: $movieRaw['Rated'],
            Released: $movieRaw['Released'],
            Genre: $movieRaw['Genre'],
            Plot: $movieRaw['Plot'],
            Poster: $movieRaw['Poster'],
            imdbID: $movieRaw['imdbID'],
            Type: $movieRaw['Type'],
        );
    }
}