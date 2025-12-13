@php
    // Nhận tham số selector, mặc định là #category_id
    $categorySelectId = $categorySelectId ?? 'category_id';
@endphp

<!-- Modal Thêm Thể Loại -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addCategoryModalLabel">
                    <i class="fas fa-plus"></i> Thêm Thể Loại Mới
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addCategoryForm" data-edit="false">
                <input type="hidden" id="category_id_editing" name="id" />
                <div class="modal-body">
                    <div class="alert alert-danger" id="categoryError" style="display:none;"></div>
                    <div class="form-group">
                        <label for="category_name">Tên thể loại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="category_description">Mô tả</label>
                        <textarea class="form-control" id="category_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success" id="saveCategoryBtn">
                        <span class="spinner-border spinner-border-sm" role="status" style="display:none;" id="categorySpinner"></span>
                        <span id="categoryModalSubmitTxt">Lưu</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let isEditing = false;
        let editingId = null;
        // SỰ KIỆN MỞ MODAL SỬA
        window.openEditCategoryModal = function(id) {
            isEditing = true;
            editingId = id;
            $('#addCategoryForm').attr('data-edit', true);
            $('#addCategoryForm')[0].reset();
            $('#categoryError').hide();
            // Đổi header và button
            $('#addCategoryModalLabel').html('<i class="fas fa-edit"></i> Sửa Thể Loại');
            $('#categoryModalSubmitTxt').text('Cập nhật');
            // LOAD DỮ LIỆU
            $.ajax({
                url: '/admin/edit_news_category/' + id,
                method: 'GET',
                dataType: 'json',
                success: function(resp) {
                    $('#category_id_editing').val(resp.id);
                    $('#category_name').val(resp.name);
                    $('#category_description').val(resp.description);
                    $('#addCategoryModal').modal('show');
                },
                error: function() {
                    alert('Không lấy được thông tin thể loại!');
                }
            });
        };
        // Khi mở modal dạng thêm mới
        $('#addCategoryModal').on('show.bs.modal', function(e) {
            if (!isEditing) {
                $('#addCategoryForm').attr('data-edit', false);
                $('#category_id_editing').val('');
                $('#categoryError').hide();
                $('#addCategoryModalLabel').html('<i class="fas fa-plus"></i> Thêm Thể Loại Mới');
                $('#categoryModalSubmitTxt').text('Lưu');
            }
        });
        // Reset về mặc định khi đóng modal
        $('#addCategoryModal').on('hidden.bs.modal', function() {
            isEditing = false;
            editingId = null;
            $('#addCategoryForm')[0].reset();
            $('#categoryError').hide();
        });
        // ... giữ lại sự kiện submit phía dưới, sẽ sửa xử lý route động ...
        $('#addCategoryForm').on('submit', function(e) {
            e.preventDefault();
            const saveBtn = $('#saveCategoryBtn');
            const spinner = $('#categorySpinner');
            const errorDiv = $('#categoryError');
            const categorySelectId = '{{ $categorySelectId }}';
            const select = $('#' + categorySelectId);
            saveBtn.prop('disabled', true);
            spinner.show();
            errorDiv.hide();
            let url = isEditing
                ? '/admin/update_news_category/' + editingId
                : '{{ route("store_news_category") }}';
            let method = isEditing ? 'POST' : 'POST';
            let data = {
                name: $('#category_name').val(),
                description: $('#category_description').val(),
                _token: '{{ csrf_token() }}'
            };
            if (isEditing) { data.id = editingId; }
            $.ajax({
                url: url,
                type: method,
                data: data,
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                },
                success: function(response) {
                    if (response.success) {
                        if (!isEditing) {
                            // Thêm option mới
                            const option = new Option(response.category.name, response.category.id);
                            select.append(option);
                            select.val(response.category.id);
                        } else {
                            // Chỉnh sửa trên bảng, reload hoặc sửa ngay (nếu muốn)
                            location.reload();
                        }
                        $('#addCategoryForm')[0].reset();
                        $('#addCategoryModal').modal('hide');
                        alert(response.message);
                    } else {
                        let errorMessage = response.message || 'Có lỗi xảy ra';
                        if (response.errors) {
                            const errors = Object.values(response.errors).flat();
                            errorMessage = errors.join('<br>');
                        }
                        errorDiv.html(errorMessage).show();
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Có lỗi xảy ra.';
                    if (xhr.status === 422) {
                        try { const errors = JSON.parse(xhr.responseText).errors;
                            const errorMessages = Object.values(errors).flat();
                            errorMessage = errorMessages.join('<br>'); } catch (e) {}
                    }
                    errorDiv.html(errorMessage).show();
                },
                complete: function() {
                    saveBtn.prop('disabled', false);
                    spinner.hide();
                }
            });
        });
    });
</script>
@endpush

