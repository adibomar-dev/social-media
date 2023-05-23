<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    // Define categories.
    private $categories = [
        'shopping', 'memes', 'random'
    ];

    /**
     * Home page view.
     *
     * @return View
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Get feed posts. All the data retrieved from random APIs.
     *
     * @return array
     */
    public function getFeedPost()
    {
        // Feed post holder.
        $feedResults = [];

        // Get user from randomuser API.
        $getMainUsers = Http::get('https://randomuser.me/api/?inc=name,picture&results='.rand(10, 15));

        foreach ($getMainUsers['results'] as $userIndex => $mainUser) {
            // Assign user data. Manipulate data in the private function.
            $feedResults[$userIndex]['user'] = $this->manageUserData($mainUser);

            // Assign the post data.
            $feedResults[$userIndex]['post'] = $this->managePostData();
        }

        // Sort to place the relevant on top.
        usort($feedResults, function ($prev, $cur) {
            return  $cur['post']['numberOfVotes'] - $prev['post']['numberOfVotes'];
        });

        // Return response in JSON format.
        return response()->json($feedResults);
    }

    /**
     * Used to manipulate user data.
     *
     * @param array $userData
     * @return array
     */
    private function manageUserData($userData)
    {
        return [
            'name' => $userData['name']['first'].' '.$userData['name']['last'],
            'image' => $userData['picture']['medium']
        ];
    }

    /**
     * Used to manipulate feed post.
     *
     * @return array
     */
    private function managePostData()
    {
        // Get random categories.
        $category = $this->categories[rand(0, (count($this->categories) - 1))];

        // Pill colour.
        $pillColour = '';

        // Set as an array to handle different categories.
        $feedContent = [];

        switch ($category) {
            case 'memes':
                $feedContent = $this->getRandomMemes();
                $pillColour = 'secondary';
            break;
            case 'random':
                $feedContent = $this->getRandomFacts();
                $pillColour = 'warning';
            break;
            case 'shopping':
                $feedContent = $this->getRandomShoppingItem();
                $pillColour = 'primary';
            break;
        }

        return [
            'category' => $category,
            'color' => $pillColour,
            'numberOfVotes' => number_format(rand(-10, 999)), // Random number of votes.
            'isLiked' => (bool) rand(0, 1), // Random like or not.
            'isRepost' => (bool) rand(0, 1), // Random repost or not.
            'feedContent' => $feedContent
        ];
    }

    /**
     * Get random memes from opensource API.
     * rand(0, 99) because the API will return 100 memes.
     *
     * @return array
     */
    private function getRandomMemes()
    {
        $getMemes = Http::get('https://api.imgflip.com/get_memes');
        $selectedMemes = $getMemes->json()['data']['memes'][rand(0, 99)];
        return [
            'post' => [
                'text' => $selectedMemes['name'],
                'image' => $selectedMemes['url']
            ]
        ];
    }

    /**
     * Get random facts from opensource API.
     *
     * @return array
     */
    private function getRandomFacts()
    {
        $getFacts = Http::get('https://uselessfacts.jsph.pl/api/v2/facts/random');
        return [
            'post' => [
                'text' => $getFacts['text']
            ]
        ];
    }

    /**
     * Get random shopping item from opensource API.
     *
     * @return array
     */
    private function getRandomShoppingItem()
    {
        $getItem = Http::get('https://fakestoreapi.com/products/'.rand(1, 20));
        $randomSellingStart = [
            'Selling my ',
            'Help! I need some money :( <br><br> I\'m selling my ',
            'Free Item! <br><br>',
            'New Item! Come and shop! <br><br>'
        ];

        return [
            'post' => [
                'text' => $randomSellingStart[rand(0, (count($randomSellingStart) - 1))].$getItem['title'].'! <br><br>'.$getItem['description'],
                'image' => $getItem['image']
            ]
        ];
    }
}
