<!-- resources/views/import-form.blade.php -->

<form action="/import" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file" accept=".xlsx, .xls">
    <button type="submit">Import</button>
</form>
