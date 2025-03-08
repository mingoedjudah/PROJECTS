document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn-comment').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-comment-id');
            const form = document.querySelector(`#delete-form-${commentId}`);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(form.action, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            const commentElement = document.querySelector(`.comment[data-comment-id="${commentId}"]`);
                            if (commentElement) {
                                commentElement.remove();
                                const commentCountElement = document.querySelector(`#comment-count-${data.postId}`);
                                if (commentCountElement) {
                                    let currentCount = parseInt(commentCountElement.textContent, 10);
                                    commentCountElement.textContent = currentCount - 1;
                                }
                                Swal.fire('Deleted!', 'Your comment has been deleted.', 'success');
                            } else {
                                Swal.fire('Error!', 'Comment element not found.', 'error');
                            }
                        } else {
                            Swal.fire('Error!', 'There was an issue deleting the comment.', 'error');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'There was an issue deleting the comment.', 'error');
                    });
                }
            });
        });
    });
});

/**
 * edit comment 
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-btn-comment').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-comment-id');
            document.querySelector(`#edit-form-${commentId}`).classList.remove('d-none');
            document.querySelector(`#comment-content-${commentId}`).classList.add('d-none');
        });
    });

    document.querySelectorAll('.cancel-edit').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.getAttribute('data-comment-id');
            document.querySelector(`#edit-form-${commentId}`).classList.add('d-none');
            document.querySelector(`#comment-content-${commentId}`).classList.remove('d-none');
        });
    });

    document.querySelectorAll('.edit-comment-form').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); 

            const formData = new FormData(this);
            const commentId = formData.get('comment_id'); 

            const url = `/comments/${commentId}`;

            fetch(url, {
                method: 'POST', 
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': formData.get('_token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`#comment-content-${commentId}`).textContent = data.comment.content;
                    document.querySelector(`#edit-form-${commentId}`).classList.add('d-none');
                    document.querySelector(`#comment-content-${commentId}`).classList.remove('d-none');
                } else {
                    alert('An error occurred while updating the comment.');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
