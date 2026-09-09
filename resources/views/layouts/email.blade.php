<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Send Email</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('failed'))
                        <div class="alert alert-danger">{{ session('failed') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('send-email') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="emailRecipient" class="form-label">To:</label>
                            <input type="email" class="form-control" name="emailRecipient" id="emailRecipient" required>
                        </div>

                        <div class="mb-3">
                            <label for="emailCc" class="form-label">CC:</label>
                            <input type="email" class="form-control" name="emailCc" id="emailCc">
                        </div>

                        <div class="mb-3">
                            <label for="emailBcc" class="form-label">BCC:</label>
                            <input type="email" class="form-control" name="emailBcc" id="emailBcc">
                        </div>

                        <div class="mb-3">
                            <label for="emailSubject" class="form-label">Subject:</label>
                            <input type="text" class="form-control" name="emailSubject" id="emailSubject" required>
                        </div>

                        <div class="mb-3">
                            <label for="emailBody" class="form-label">Message:</label>
                            <textarea class="form-control" name="emailBody" id="emailBody" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="emailAttachments" class="form-label">Attachment:</label>
                            <input type="file" class="form-control" name="emailAttachments[]" id="emailAttachments" multiple>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Send Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>