<?php

namespace app\components\zoom\services;

use app\components\zoom\ZoomComponent;
use app\components\zoom\ZoomServiceInterface;
use yii\base\BaseObject;

class Users extends BaseObject implements ZoomServiceInterface
{
    /**
     * @var ZoomComponent
     */
    protected $zoom;

    /**
     * Users constructor.
     * @param ZoomComponent $zoom
     * @param array $config
     */
    public function __construct(ZoomComponent $zoom, $config = [])
    {
        $this->zoom = $zoom;
        parent::__construct($config);
    }

    /**
     * List users on your account.
     * @param array $params Optional query parameters (e.g., status, page_size, page_number).
     * @return array|false The list of users or false on failure.
     */
    public function listUsers($params = ['page_size' => 10])
    {
        // In a real application with API keys, this would return actual data.
        if (empty($this->zoom->apiKey) || empty($this->zoom->apiSecret)) {
            \Yii::info("Simulating listUsers API call. API keys not set.", __METHOD__);
            // Simulate a successful response with dummy data
            return [
                'page_count' => 1,
                'page_number' => 1,
                'page_size' => $params['page_size'] ?? 1,
                'total_records' => 2,
                'users' => [
                    [
                        'id' => 'dummyUserId1',
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'john.doe@example.com',
                        'type' => 1,
                        'status' => 'active',
                        'created_at' => date('Y-m-d\TH:i:s\Z', strtotime('-10 days')),
                    ],
                    [
                        'id' => 'dummyUserId2',
                        'first_name' => 'Jane',
                        'last_name' => 'Doe',
                        'email' => 'jane.doe@example.com',
                        'type' => 1,
                        'status' => 'active',
                        'created_at' => date('Y-m-d\TH:i:s\Z', strtotime('-5 days')),
                    ]
                ]
            ];
        }
        return $this->zoom->makeRequest('users', 'GET', $params);
    }

    /**
     * Get details for a specific user.
     * @param string $userId The ID of the user. Typically 'me' for the authenticated user.
     * @return array|false The user details or false on failure.
     */
    public function getUser($userId = 'me')
    {
        if (empty($this->zoom->apiKey) || empty($this->zoom->apiSecret)) {
            \Yii::info("Simulating getUser API call for user {$userId}. API keys not set.", __METHOD__);
            // Simulate a successful response with dummy data
            if ($userId === 'me' || $userId === 'dummyUserId1') {
                return [
                    'id' => 'dummyUserId1',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john.doe@example.com',
                    'type' => 1,
                    'status' => 'active',
                    'created_at' => date('Y-m-d\TH:i:s\Z', strtotime('-10 days')),
                    'last_login_time' => date('Y-m-d\TH:i:s\Z', strtotime('-1 day')),
                ];
            } elseif ($userId === 'dummyUserId2') {
                 return [
                    'id' => 'dummyUserId2',
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'email' => 'jane.doe@example.com',
                    'type' => 1,
                    'status' => 'active',
                    'created_at' => date('Y-m-d\TH:i:s\Z', strtotime('-5 days')),
                    'last_login_time' => date('Y-m-d\TH:i:s\Z', strtotime('-2 hours')),
                ];
            }
            return false; // User not found
        }
        return $this->zoom->makeRequest("users/{$userId}", 'GET');
    }

    // Add other user-related methods here (e.g., createUser, updateUser, deleteUser, etc.)
}
