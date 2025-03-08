$(document).ready(function() {
    let lastNotificationId = 0;

    setInterval(function() {
        $.ajax({
            url: '/notifications/check',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    updateNotificationCount(response.notificationCount);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }, 5000);

    $('.notification-button').on('click', function() {
        loadNotifications();
    });

    $('.notifications').on('click', function() {
        markNotificationsAsRead();
    });

    function loadNotifications() {
        $.ajax({
            url: '/notifications/check',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('.notifications').empty();
                    appendNewNotifications(response.notifications);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function updateNotificationCount(count) {
        if (count > 0) {
            if (!$('.badge-number').length) {
                $('.nav-icon').append('<span class="badge bg-danger badge-number">' + count + '</span>');
            } else {
                $('.badge-number').text(count).show();
            }
        } else {
            $('.badge-number').remove();
        }
    }

    function appendNewNotifications(notifications) {
        notifications.forEach(function(notification) {
            const username = notification.username || 'Unknown';
            const profilePicture = notification.profilePicture || 'assets-user/img/none-profile.jpg';
            const notificationType = notification.type;
            const timeAgo = formatDate(notification.created_at);

            const notificationId = notification.id;

            if (!$(`.notification-item[data-id="${notificationId}"]`).length) {
                const notificationItem = `
                    <li class="notification-item ${notification.status === 'unread' ? 'unread' : ''}" data-id="${notificationId}">
                        <a href="#">
                            <div class="d-flex">
                                <div class="pe-3">
                                    <img src="${profilePicture}" alt="${username}" class="rounded-circle" style="width: 40px; height: 40px;">
                                </div>
                                <div class="text-wrap">
                                    <p class="text-sm mb-1">
                                        <strong>${username}</strong> 
                                        ${getNotificationMessage(notificationType)}
                                    </p>
                                    <p class="text-muted mb-0">${timeAgo}</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                `;
                $('.notifications').prepend(notificationItem);
            }
        });
    }

    function getNotificationMessage(type) {
        switch(type) {
            case 'like':
                return 'liked your post';
            case 'comment':
                return 'commented on your post';
            case 'reply':
                return 'replied to your comment';
            default:
                return 'sent you a notification';
        }
    }

    function markNotificationsAsRead() {
        $.ajax({
            url: '/notifications/mark-read',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('.badge-number').remove();
                    $('.notification-item.unread').each(function() {
                        $(this).css('background-color', 'transparent').removeClass('unread');
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function formatDate(dateString) {
        return moment(dateString).fromNow();
    }
});
