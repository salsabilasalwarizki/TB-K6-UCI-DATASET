<form action="{{ route('admin.papers.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv_file" accept=".csv" required>
    <button type="submit">Upload CSV</button>
</form>