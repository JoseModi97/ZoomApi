<?php

namespace app\components\zoom\services;

use app\components\zoom\ZoomComponent;
use app\components\zoom\ZoomServiceInterface;
use yii\base\BaseObject;

class Meetings extends BaseObject implements ZoomServiceInterface
{
    /**
     * @var ZoomComponent
     */
    protected $zoom;

    /**
     * Meetings constructor.
     * @param ZoomComponent $zoom
     * @param array $config
     */
    public function __construct(ZoomComponent $zoom, $config = [])
    {
        $this->zoom = $zoom;
        parent::__construct($config);
    }

    /**
     * List meetings for a user.
     * @param string $userId The ID of the user. Typically 'me' for the authenticated user.
     * @param array $params Optional query parameters (e.g., type, page_size, next_page_token).
     * @return array|false The list of meetings or false on failure.
     */
    public function listMeetings($userId = 'me', $params = [])
    {
        // In a real application with API keys, this would return actual data.
        if (empty($this->zoom->apiKey) || empty($this->zoom->apiSecret)) {
            \Yii::info("Simulating listMeetings API call for user {$userId}. API keys not set.", __METHOD__);
            // Simulate a successful response with dummy data
            return [
                'page_count' => 1,
                'page_number' => 1,
                'page_size' => 1,
                'total_records' => 1,
                'meetings' => [
                    [
                        'uuid' => 'dummyMeetingUUID123',
                        'id' => '123456789',
                        'host_id' => $userId,
                        'topic' => 'My Simulated Meeting',
                        'type' => 2, // Scheduled meeting
                        'start_time' => date('Y-m-d\TH:i:s\Z', strtotime('+1 day')),
                        'duration' => 60,
                        'timezone' => 'UTC',
                        'created_at' => date('Y-m-d\TH:i:s\Z'),
                        'join_url' => 'https://example.zoom.us/j/123456789',
                        'status' => 'waiting'
                    ]
                ]
            ];
        }
        return $this->zoom->makeRequest("users/{$userId}/meetings", 'GET', $params);
    }

    /**
     * Create a meeting for a user.
     * @param string $userId The ID of the user. Typically 'me' for the authenticated user.
     * @param array $data Meeting data (e.g., topic, type, start_time, duration).
     *        Refer to Zoom API documentation for required and optional fields.
     *        Example:
     *        [
     *            'topic' => 'New Team Meeting',
     *            'type' => 2, // Scheduled meeting
     *            'start_time' => '2024-08-15T10:00:00Z', // Use ISO 8601 format, UTC
     *            'duration' => 60, // In minutes
     *            'timezone' => 'UTC', // Optional, specify timezone
     *            'settings' => [
     *                'join_before_host' => false,
     *                'mute_upon_entry' => true,
     *            ]
     *        ]
     * @return array|false The created meeting details or false on failure.
     */
    public function createMeeting($userId = 'me', $data = [])
    {
        if (empty($this->zoom->apiKey) || empty($this->zoom->apiSecret)) {
            \Yii::info("Simulating createMeeting API call for user {$userId}. API keys not set.", __METHOD__);
            // Simulate a successful response with dummy data
            return array_merge([
                'uuid' => 'dummyCreatedMeetingUUID456',
                'id' => '987654321',
                'host_id' => $userId,
                'created_at' => date('Y-m-d\TH:i:s\Z'),
                'join_url' => 'https://example.zoom.us/j/987654321',
                'status' => 'waiting'
            ], $data);
        }
        return $this->zoom->makeRequest("users/{$userId}/meetings", 'POST', $data);
    }

    /**
     * Get details of a specific meeting.
     * @param string $meetingId The ID or UUID of the meeting.
     * @return array|false The meeting details or false on failure.
     */
    public function getMeeting($meetingId)
    {
        if (empty($this->zoom->apiKey) || empty($this->zoom->apiSecret)) {
            \Yii::info("Simulating getMeeting API call for meeting {$meetingId}. API keys not set.", __METHOD__);
            // Simulate a successful response with dummy data
            return [
                'uuid' => $meetingId,
                'id' => ($meetingId === 'dummyMeetingUUID123' || $meetingId === '123456789') ? '123456789' : 'genericId'.$meetingId,
                'host_id' => 'dummyHostId',
                'topic' => 'Simulated Meeting Details',
                'type' => 2,
                'start_time' => date('Y-m-d\TH:i:s\Z', strtotime('+1 day')),
                'duration' => 60,
                'timezone' => 'UTC',
                'created_at' => date('Y-m-d\TH:i:s\Z'),
                'join_url' => 'https://example.zoom.us/j/' . (($meetingId === 'dummyMeetingUUID123' || $meetingId === '123456789') ? '123456789' : 'genericId'.$meetingId),
                'status' => 'waiting'
            ];
        }
        return $this->zoom->makeRequest("meetings/{$meetingId}", 'GET');
    }

    // Add other meeting-related methods here (e.g., updateMeeting, deleteMeeting, etc.)
}
