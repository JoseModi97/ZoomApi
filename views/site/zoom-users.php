<?php

/* @var $this yii\web\View */
/* @var $users array */
/* @var $debugInfo string */
/* @var $isApiConfigured bool */

use yii\helpers\Html;

$this->title = 'Zoom Users Example';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-zoom-users">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This page demonstrates fetching a list of users from the Zoom API (currently using simulated data).</p>

    <?php if (!$isApiConfigured): ?>
        <div class="alert alert-warning">
            <strong>Warning:</strong> Zoom API key and secret are not configured or are using placeholder values.
            The component is currently returning simulated data.
            Please update your <code>config/web.php</code> and <code>config/console.php</code> with actual Zoom API credentials.
            Refer to <code>components/zoom/AGENTS.md</code> for more details.
        </div>
    <?php endif; ?>

    <div class="alert alert-info">
        <strong>Debug Info:</strong> <?= Html::encode($debugInfo) ?>
    </div>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($users)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= Html::encode($user['id']) ?></td>
                        <td><?= Html::encode($user['first_name']) ?></td>
                        <td><?= Html::encode($user['last_name']) ?></td>
                        <td><?= Html::encode($user['email']) ?></td>
                        <td><?= Html::encode($user['type']) ?></td>
                        <td><?= Html::encode($user['status']) ?></td>
                        <td><?= Html::encode($user['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found or an error occurred.</p>
    <?php endif; ?>

    <hr>
    <h3>Next Steps:</h3>
    <ul>
        <li>Configure your actual Zoom API Key and Secret in <code>config/web.php</code> and <code>config/console.php</code>.</li>
        <li>Implement the actual OAuth 2.0 token retrieval logic in <code>components/zoom/ZoomComponent::getAccessToken()</code>.
            (Refer to <code>ZOOM_OAUTH_SETUP_GUIDE.md</code> if you have one, or the official Zoom API documentation for Server-to-Server OAuth or standard OAuth app setup).</li>
        <li>Expand the service classes (<code>components/zoom/services/Users.php</code>, <code>components/zoom/services/Meetings.php</code>) with more API methods as needed.</li>
        <li>Add proper error handling and logging for production use.</li>
    </ul>

</div>
