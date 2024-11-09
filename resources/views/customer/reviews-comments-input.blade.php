<div class="modal fade" id="comment-modal-input" tabindex="-1" role="dialog" aria-labelledby="comment-modal-input-label"
    aria-hidden="true">
    <div class="modal-dialog comment-modal-input-dialog" role="document">
        <div class="modal-content comment-modal-input-content">
            <div class="modal-header comment-modal-input-header">
                <div class="flex items-center gap-2">
                    <img class="avatar-img"
                        src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png"
                        alt="Avatar" class="w-full h-48 object-cover">
                    <p>John Doe</p>
                </div>
                <button type="button" class="close" onclick="closeCommentModalInput()">&times;</button>
            </div>
            <div class="modal-body text-center comment-modal-input-body">
                <x-adminlte-textarea name="taMsg" rows=6 igroup-size="sm" label-class="text-primary"
                    placeholder="Write your comment..." disable-feedback>
                </x-adminlte-textarea>
            </div>
            <div class="modal-footer comment-modal-input-footer">
                <button type="button" class="btn" style="background-color: #e2e2e2"
                    onclick="closeCommentModalInput()">Đóng</button>
                <button type="button" class="btn" style="background-color: rgba(233, 142, 22, 0.685); color: white"
                    onclick="applyCommentInput()">
                    <i class="fa fa-comment"></i> Xác Nhận
                </button>
            </div>
        </div>
    </div>
</div>
