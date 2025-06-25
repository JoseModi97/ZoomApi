# Agent Instructions for Zoom API Integration

This document provides guidance for AI agents working on the Zoom API integration within this Yii2 project.

## Configuration

1.  **API Credentials**:
    *   The Zoom API Key and API Secret need to be configured in the application's configuration files:
        *   `config/web.php`
        *   `config/console.php`
    *   Look for the `zoom` component configuration block:
        ```php
        'zoom' => [
            'class' => 'app\components\zoom\ZoomComponent',
            'apiKey' => 'YOUR_ZOOM_API_KEY', // Replace this placeholder
            'apiSecret' => 'YOUR_ZOOM_API_SECRET', // Replace this placeholder
            // For Server-to-Server OAuth, you might also need:
            // 'accountId' => 'YOUR_ZOOM_ACCOUNT_ID',
            // 'oauthTokenUrl' => 'https://zoom.us/oauth/token', // Default is provided
        ],
        ```
    *   **IMPORTANT**: Replace `'YOUR_ZOOM_API_KEY'` and `'YOUR_ZOOM_API_SECRET'` with the actual credentials. If using Server-to-Server OAuth, also provide your `accountId`.

2.  **OAuth Token URL**:
    *   The `oauthTokenUrl` property in `ZoomComponent.php` (and configurable in `config/web.php`/`config/console.php`) is set to the standard Zoom token URL. If your application uses a different endpoint (e.g., for a specific region or a customized setup), ensure this is updated.

## Authentication (`ZoomComponent::getAccessToken()`)

*   The current `getAccessToken()` method in `app\components\zoom\ZoomComponent.php` returns a dummy token because the API keys are not yet set.
*   **Action Required**: This method **MUST** be updated to implement the correct Zoom OAuth 2.0 flow (e.g., Server-to-Server OAuth or a standard OAuth 2.0 grant type if you're building a user-level integration).
    *   **Server-to-Server OAuth**: This is common for backend integrations where your application acts on behalf of your Zoom account. You'll typically make a POST request to the `oauthTokenUrl` with `grant_type=account_credentials` and your `account_id`, authenticated with your `apiKey` and `apiSecret` (often as a Basic Auth header).
    *   **Standard OAuth (e.g., Authorization Code Grant)**: If users will authorize your app to access their Zoom data, you'll need a more complex flow involving redirects.
*   Refer to the official Zoom API documentation for detailed instructions on the chosen authentication method. The file `ZOOM_OAUTH_SETUP_GUIDE.md` in the project root might also contain relevant setup information.
*   Consider caching the obtained access token (e.g., using Yii's cache component) until it expires to avoid requesting a new token for every API call.

## Adding New Zoom API Services

The integration is designed to be extensible for different Zoom API resources (Meetings, Users, Recordings, etc.).

1.  **Create a Service Class**:
    *   For a new service (e.g., "Recordings"), create a new PHP class in the `components/zoom/services/` directory (e.g., `components/zoom/services/Recordings.php`).
    *   The class name should be the singular, capitalized version of the service (e.g., `Recordings`).
    *   The class **MUST** implement the `app\components\zoom\ZoomServiceInterface`.
    *   The constructor should accept an `app\components\zoom\ZoomComponent` instance and call the parent constructor if extending `yii\base\BaseObject`.

    ```php
    <?php

    namespace app\components\zoom\services;

    use app\components\zoom\ZoomComponent;
    use app\components\zoom\ZoomServiceInterface;
    use yii\base\BaseObject; // Or your preferred base class

    class Recordings extends BaseObject implements ZoomServiceInterface
    {
        protected $zoom;

        public function __construct(ZoomComponent $zoom, $config = [])
        {
            $this->zoom = $zoom;
            parent::__construct($config);
        }

        // Add methods for Recordings API endpoints, e.g.:
        // public function listRecordings($userId = 'me', $params = [])
        // {
        //     return $this->zoom->makeRequest("users/{$userId}/recordings", 'GET', $params);
        // }
    }
    ```

2.  **Using the New Service**:
    *   Once the service class is created and implements the interface, you can access it via the `ZoomComponent` using its magic `__get` method. The name should be the lowercase version of the service.
        ```php
        $zoom = \Yii::$app->zoom;
        $recordings = $zoom->recordings->listRecordings('me');
        // or $meetingDetails = $zoom->meetings->getMeeting('meetingId');
        ```

## Making API Requests

*   Use the `makeRequest($endpoint, $method, $data, $options)` method of the `ZoomComponent` from within your service classes.
*   The `$endpoint` should be relative to the Zoom API base URL (e.g., `users/{userId}/meetings`).
*   The `ZoomComponent` will automatically handle adding the Authorization header with the Bearer token.

## Error Handling

*   The `makeRequest` method in `ZoomComponent` performs basic error checking and logs errors using `\Yii::error()`.
*   Service methods should check the return value of `makeRequest` (it returns `false` on failure) and handle errors appropriately (e.g., return `false`, throw an exception, or return a default value).
*   When API keys are not configured, service methods currently return simulated/dummy data. This behavior should be removed or adjusted once actual API keys are in place and the authentication flow is implemented.

## Testing

*   When implementing new features or fixing bugs:
    *   If API keys are available and configured, test against a development/sandbox Zoom account.
    *   If API keys are not available, ensure your code gracefully handles the simulated responses and clearly indicates that it's operating in a simulated mode (as done in the example `views/site/zoom-users.php`).
    *   Consider writing unit tests for the service methods, potentially mocking the `ZoomComponent::makeRequest` method to simulate API responses.

By following these guidelines, you can effectively extend and maintain the Zoom API integration.Tool output for `create_file_with_block`:
