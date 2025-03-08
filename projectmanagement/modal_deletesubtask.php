<?php
require_once('../conn/db.php');
require_once('../check_session.php');

$taskId = $_GET['taskId'] ?? 0;

// Get subtask details
$query = "SELECT subject FROM pm_projecttasktb WHERE id = '$taskId' AND type = 'subtask'";
$result = mysqli_query($mysqlconn, $query);
$subtask = mysqli_fetch_assoc($result);
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="deleteSubtaskModalLabel">Delete Subtask</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <p>Are you sure you want to delete this subtask?</p>
                    <?php if ($subtask): ?>
                        <p><strong>Subtask:</strong> <?php echo htmlspecialchars($subtask['subject']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" onclick="deleteSubtask(<?php echo $taskId; ?>)">
                <i class="fa fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>

<script>
function deleteSubtask(taskId) {
    $.ajax({
        url: 'delete_subtask.php',
        type: 'POST',
        data: { taskId: taskId },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Subtask has been deleted.',
                    icon: 'success',
                    width: '32em',
                    customClass: {
                        popup: 'swal-large',
                        title: 'swal-title-large',
                        htmlContainer: 'swal-text-large'
                    }
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error deleting subtask: ' + response.message,
                    icon: 'error',
                    width: '32em',
                    customClass: {
                        popup: 'swal-large',
                        title: 'swal-title-large',
                        htmlContainer: 'swal-text-large'
                    }
                });
            }
            $('#deleteSubtaskModal').modal('hide');
        },
        error: function() {
            Swal.fire({
                title: 'Error!',
                text: 'Error occurred while processing the request',
                icon: 'error',
                width: '32em',
                customClass: {
                    popup: 'swal-large',
                    title: 'swal-title-large',
                    htmlContainer: 'swal-text-large'
                }
            });
            $('#deleteSubtaskModal').modal('hide');
        }
    });
}
</script>
