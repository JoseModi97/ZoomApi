# Guide: Registering Your Zoom OAuth App & Obtaining Credentials

This guide outlines the steps to register an OAuth application on the Zoom App Marketplace, obtain your `Client ID` and `Client Secret`, and configure your `Redirect URI`. These are essential for integrating Zoom API functionalities into your application.

## Steps:

1.  **Sign in to the Zoom App Marketplace:**
    *   Navigate to [https://marketplace.zoom.us/](https://marketplace.zoom.us/).
    *   Sign in using your Zoom account credentials.

2.  **Initiate App Creation:**
    *   In the top-right corner, click on the **Develop** dropdown menu.
    *   Select **Build App**.

3.  **Choose App Type - OAuth:**
    *   Zoom will present different app types. Select **OAuth**.
    *   Click the **Create** button.

4.  **Define App Name and Type:**
    *   **App Name:** Provide a descriptive name for your application (e.g., "My Yii2 Zoom Manager", "Project Zoom Integration").
    *   **App Type:**
        *   **Account-level app:** Choose this if the application is primarily for users within your own Zoom account. This is often simpler for initial development or internal tools.
        *   **User-managed app:** Choose this if you intend for any Zoom user to be able to install and use your application.
    *   **Publication:** Decide if you want to publish this app on the Zoom App Marketplace. For internal development or private tools, you would typically uncheck "Would you like to publish this app on Zoom App Marketplace?"
    *   Click **Create**.

5.  **Access App Credentials:**
    *   Once the app is created, you will be directed to its configuration dashboard.
    *   Locate and navigate to the **"App Credentials"** tab or section.
    *   On this page, you will find:
        *   **`Client ID`**: Your application's unique public identifier.
        *   **`Client Secret`**: Your application's confidential secret. **Treat this like a password and keep it secure.**
    *   Copy both the `Client ID` and `Client Secret` and store them in a safe place.

6.  **Provide Basic Information:**
    *   Go to the **"Information"** tab (or a similarly named section for basic details).
    *   Fill in the required information, such as:
        *   Short Description
        *   Long Description
        *   Company Name
        *   Developer Contact Information (Name, Email address)

7.  **Configure Redirect URI for OAuth (Crucial):**
    *   This is a critical step for the OAuth 2.0 flow to function correctly.
    *   In your app's configuration settings, find the section labeled "Redirect URL for OAuth" or "Add Allow List URLs" (the exact wording might vary).
    *   You need to add the **`Redirect URI`** (also known as a Callback URL). This is the specific URL within *your* application where Zoom will redirect the user after they have successfully authorized (or denied) access to their Zoom account.
    *   **Examples:**
        *   **For Local Development:**
            *   `http://localhost/zoom/callback`
            *   `http://localhost:8080/zoom/callback` (if your local server uses port 8080)
            *   `http://my-yii2-app.test/zoom/callback` (if you use a custom local domain like `my-yii2-app.test` and your Yii2 route is `zoom/callback`)
            *   *Note: The path `/zoom/callback` corresponds to a controller action you will create in your Yii2 application (e.g., `ZoomController` with `actionCallback`).*
        *   **For Production Environment:**
            *   `https://youractualdomain.com/zoom/callback`
    *   **Important:** You must add this exact URI to the "OAuth Allow List" or "Redirect URL for OAuth" field in your Zoom app settings. Any mismatch will cause the OAuth flow to fail. You can typically add multiple redirect URIs if needed (e.g., one for development, one for staging, one for production).

8.  **Define Required Scopes:**
    *   Navigate to the **"Scopes"** tab. Scopes define the permissions your application requests from the user.
    *   Click **"Add Scopes"** and select the specific permissions your application will need to access Zoom API endpoints. Based on your project plan, you will likely need scopes related to:
        *   **Meeting:** (e.g., `meeting:read:admin`, `meeting:write:admin` for viewing and managing meetings)
        *   **Recording:** (e.g., `recording:read:admin` for accessing meeting recordings)
        *   **User:** (e.g., `user:read:admin` to get user profile information if needed)
        *   **Report:** (e.g., `report:read:admin` for accessing participant reports)
        *   **Webhook:** (Eventual webhook functionality might require specific event subscriptions, which often align with certain scopes).
    *   It's good practice to start with the minimum necessary scopes and only add more as your application's feature set grows. Requesting excessive scopes can be a security concern and might deter users from authorizing your app.

9.  **Activation (Primarily for Account-level apps):**
    *   If you created an "Account-level app," it might require an explicit activation step for it to be usable within your Zoom account. This is usually a simple click ("Activate your app") within the Zoom Marketplace interface for your specific app.

## Summary of Information to Obtain:

*   **`Client ID`**: Found in the "App Credentials" section of your Zoom app settings.
*   **`Client Secret`**: Found in the "App Credentials" section. Keep this confidential.
*   **`Redirect URI`**: This is the URL *you define* for your application's OAuth callback endpoint. You must then register this exact URI in your Zoom app's "Redirect URL for OAuth" or "OAuth Allow List" settings.

---

Once you have completed these steps on the Zoom App Marketplace, you will possess the necessary `Client ID`, `Client Secret`, and will have your `Redirect URI` configured in Zoom, ready for use in your application's OAuth 2.0 implementation.
