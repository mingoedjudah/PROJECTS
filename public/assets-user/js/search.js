$(document).ready(function() {
    const searchRoute = $('meta[name="search-route"]').attr('content');
    const loggedInUserId = $('meta[name="logged-in-user-id"]').attr('content');

    $('#search-query').on('keyup', function() {
        let query = $(this).val();
        if (query.length > 0) { 
            $.ajax({
                url: searchRoute,
                type: "GET",
                data: {'query': query},
                success: function(data) {
                    let results = $('#search-results');
                    results.empty();
                    if (data.length > 0) {
                        data.forEach(function(user) {
                            let profilePicUrl = user.profile_picture ? `/storage/${user.profile_picture}` : '/storage/profile_pictures/default.png';
                            console.log('Profile Picture URL:', profilePicUrl);

                            let profileLink = user.user_id == loggedInUserId
                                ? `/user/profile`
                                : `/user/profile-user/${user.user_id}`;

                            let userItem = `
                                <div class="user-result">
                                    <img src="${profilePicUrl}" alt="${user.first_name} ${user.last_name}'s Profile Picture" class="profile-pic" onerror="this.onerror=null; this.src='/assets-user/img/none-profile.jpg';">
                                    <span>
                                        <a href="${profileLink}">
                                            ${user.first_name} ${user.last_name} (@${user.username})
                                        </a>
                                    </span>
                                </div>
                            `;
                            results.append(userItem);
                        });
                    } else {
                        results.append('<div>No results found</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + error);
                    $('#search-results').html('<div>An error occurred while searching</div>');
                }
            });
        } else {
            $('#search-results').empty();
        }
    });
});
