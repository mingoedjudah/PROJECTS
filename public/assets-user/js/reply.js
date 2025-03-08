$(document).ready(function() {
    $(document).on('click', '.reply-btn', function() {
        console.log('Reply button clicked');
        var replyForm = $(this).closest('.comment').find('.reply-form');
        console.log('Reply form found:', replyForm.length > 0);
        replyForm.removeClass('d-none');  
    });

    function toggleReplies() {
        var $this = $(this);
        var repliesList = $this.siblings('.replies-list');
        var icon = $this.find('i');

        if (repliesList.hasClass('d-none')) {
            repliesList.removeClass('d-none');
            icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        } else {
            repliesList.addClass('d-none');
            icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    }

    $(document).on('submit', '.reply-form form', function(e) {
        e.preventDefault();
        var form = $(this);
        var commentContainer = form.closest('.comment');
        var commentId = form.find('input[name="parent_id"]').val();
        var url = `/comments/${commentId}/reply`;

        var formData = new FormData(this);

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': window.csrfToken, 
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success && response.comment) {
                    var comment = response.comment;
                    var replyHtml = `
                        <div class="comment d-flex mb-2 p-2 border-bottom" id="reply-${comment.comment_id}">
                            <img src="${comment.user.profile_picture ? '/storage/' + comment.user.profile_picture : '/assets-user/img/none-profile.jpg'}" alt="User" class="rounded-circle small-img">
                            <div class="ms-2 flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>${comment.user.username}</strong>
                                        <div class="text-muted" style="font-size:13px;">
                                            Just now
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-link btn-sm more-options-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item edit-reply-btn" href="#" data-reply-id="${comment.comment_id}" data-comment-id="${comment.parent_id}"><i class="fas fa-edit"></i> Edit</a></li>
                                            <li><a class="dropdown-item delete-reply-btn" href="#" data-reply-id="${comment.comment_id}" data-comment-id="${comment.parent_id}"><i class="fas fa-trash"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="mb-1 reply-content">${comment.content}</p>
                                <div class="edit-reply-form d-none">
                                    <form method="POST" action="/comments/${comment.parent_id}/replies/${comment.comment_id}" class="d-flex align-items-center">
                                        <input type="hidden" name="_method" value="${window.httpMethod}">
                                        <input type="hidden" name="_token" value="${window.csrfToken}">
                                        <input type="text" class="form-control me-2" name="content" value="${comment.content}" required>
                                        <button class="btn btn-primary btn-sm me-2" type="submit">Save</button>
                                        <button type="button" class="btn btn-secondary btn-sm cancel-edit-btn">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    `;

                    var repliesContainer = commentContainer.find('.replies-container');
                    var repliesList = repliesContainer.find('.replies-list');
                    
                    if (repliesList.length === 0) {
                        repliesList = $('<div class="replies-list"></div>');
                        repliesContainer.append(repliesList);
                    }
                    
                    repliesList.append(replyHtml);
                    form.find('input[name="content"]').val('');

                    var viewRepliesBtn = repliesContainer.find('.view-replies-btn');
                    var newCount = repliesList.children().length;

                    if (viewRepliesBtn.length === 0) {
                        var newViewRepliesBtn = $(`
                            <button class="btn btn-link btn-sm view-replies-btn" data-comment-id="${comment.parent_id}">
                                View <span class="reply-count">${newCount}</span> ${newCount === 1 ? 'reply' : 'replies'}
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        `);
                        repliesContainer.prepend(newViewRepliesBtn);
                        
                        newViewRepliesBtn.on('click', toggleReplies);
                    } else {
                        viewRepliesBtn.html(`View <span class="reply-count">${newCount}</span> ${newCount === 1 ? 'reply' : 'replies'} <i class="fas fa-chevron-down"></i>`);
                    }

                    repliesList.removeClass('d-none');
                    viewRepliesBtn.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');

                    form.closest('.reply-form').addClass('d-none');
                } else {
                    console.error('Unexpected response structure:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.log('Status:', status);
                console.log('Response:', xhr.responseText);

                var errors = xhr.responseJSON.errors;
                var errorMessages = '';
                for (var field in errors) {
                    errorMessages += errors[field].join(', ') + '\n';
                }
                alert('Please correct the following errors:\n' + errorMessages);
            }
        });
    });


    $(document).on('click', '.view-replies-btn', toggleReplies);

    $(document).on('click', '.edit-reply-btn', function(e) {
        e.preventDefault();
        var replyId = $(this).data('reply-id');
        var replyContainer = $('#reply-' + replyId);
        var replyContent = replyContainer.find('.reply-content');
        var editForm = replyContainer.find('.edit-reply-form');

        replyContent.addClass('d-none');
        editForm.removeClass('d-none');
    });

    $(document).on('click', '.cancel-edit-btn', function() {
        var replyContainer = $(this).closest('.comment');
        var replyContent = replyContainer.find('.reply-content');
        var editForm = replyContainer.find('.edit-reply-form');

        replyContent.removeClass('d-none');
        editForm.addClass('d-none');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.reply-form').classList.add('d-none');
        });
    });
});

/**
 * edit and delete
 */
$(document).on('submit', '.edit-reply-form form', function(e) {
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    var replyContainer = form.closest('.comment');
    var replyContent = replyContainer.find('.reply-content');

    $.ajax({
        url: url,
        method: 'POST',
        data: form.serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                replyContent.text(response.content);
                replyContent.removeClass('d-none');
                form.closest('.edit-reply-form').addClass('d-none');
            } else {
                console.error('Error updating reply:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.log('Status:', status);
            console.log('Response:', xhr.responseText);
        }
    });
});

/**
 * delete
 */
$(document).ready(function() {
    $(document).on('click', '.delete-reply-btn', function(e) {
        e.preventDefault();
        var replyId = $(this).data('reply-id');
        var commentId = $(this).data('comment-id');
        var replyContainer = $('#reply-' + replyId);
        var replyCountContainer = $(this).closest('.comment-container').find('.view-replies-btn');
        
        var currentCount = 0;
        var countMatch = replyCountContainer.text().match(/\d+/);
        if (countMatch && countMatch[0]) {
            currentCount = parseInt(countMatch[0]);
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var newCount = Math.max(0, currentCount - 1); 
                updateReplyCount(replyCountContainer, newCount);
                replyContainer.hide();

                $.ajax({
                    url: '/comments/' + commentId + '/replies/' + replyId,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            replyContainer.remove();
                            Swal.fire(
                                'Deleted!',
                                'The reply has been deleted.',
                                'success'
                            );
                        } else {
                            updateReplyCount(replyCountContainer, currentCount);
                            replyContainer.show();
                            Swal.fire(
                                'Error!',
                                'There was an issue deleting the reply.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        updateReplyCount(replyCountContainer, currentCount);
                        replyContainer.show();
                        Swal.fire(
                            'Error!',
                            'There was an issue deleting the reply.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    function updateReplyCount(container, count) {
        if (container.length) {
            container.html('View ' + count + ' ' + (count === 1 ? 'reply' : 'replies') + ' <i class="fas fa-chevron-down"></i>');
        }
    }
});

