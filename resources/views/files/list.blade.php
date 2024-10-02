<!DOCTYPE html>
<html>

<head>
  <title>Laravel File Storage with Amazon S3 </title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
</head>

<body>
  <section class="table_outer mt-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="card border-0 shadow">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover mb-0 ">
                  <thead class="table-light">
                    <tr>
                      <th scope="col">Name</th>
                      <th scope="col">Path</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($files as $file)
                    <tr>
                      <td>{{ $file->name }}</td>
                      <td>{{ $file->url }}</td>
                      <td>
                        <button type="button" class="btn btn-primary btn-sm px-2 btn-view-file" 
                            data-bs-toggle="modal" 
                            data-bs-target="#fileModal" 
                            data-url="{{ $file->full_url  }}" 
                            data-type="{{ pathinfo($file->full_url, PATHINFO_EXTENSION) }}">
                          <i class="fa-solid fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm px-2 btn-delete-file" data-id="{{ $file->id }}">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal -->
  <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileModalLabel">View File</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="fileViewer"></div>
            </div>
        </div>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script>
    $(document).ready(function () {
        $('#fileModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Nút đã được nhấn
            var url = button.data('url'); // Lấy URL từ data attribute
            var type = button.data('type'); // Lấy loại file từ data attribute
            
            var fileViewer = $('#fileViewer');
            fileViewer.empty(); // Xóa nội dung cũ trước khi thêm mới

            if (type === 'pdf') {
                fileViewer.append('<iframe src="' + url + '" style="width: 100%; height: 500px;" frameborder="0"></iframe>');
            } else {
                fileViewer.append('<img src="' + url + '" alt="File Preview" style="width: 100%; height: auto;">');
            }
        });

        $('#fileModal').on('hidden.bs.modal', function () {
            // Xóa nội dung khi modal đóng
            $('#fileViewer').empty();
        });
    });

    $(document).ready(function () {
    // Lấy CSRF token từ meta tag
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Thiết lập CSRF token cho các yêu cầu AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    // Xử lý sự kiện click nút xóa
    $(document).on('click', '.btn-delete-file', function () {
        var fileId = $(this).data('id');
        console.log(fileId)
        if (confirm('Are you sure you want to delete this file?')) {
            $.ajax({
                url: '/files/' + fileId,
                type: 'DELETE',
                success: function (response) {
                    alert(response.message);
                    location.reload();
                },
                error: function (xhr) {
                    alert('Error deleting file: ' + xhr.responseText);
                }
            });
        }
    });
});
  </script>
</body>

</html>
