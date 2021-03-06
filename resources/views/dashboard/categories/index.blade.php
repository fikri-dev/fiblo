<x-dashboard-layout title="Semua Kategori">
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Semua Kategori</h1>
        </div>

        <div class="table-responsive">
            <div class="flex mb-3">
                <x-_link href="{{ route('categories.create') }}"><span data-feather="plus"></span> Buat Kategory
                </x-_link>
            </div>

            @if ($categories->count())
            <div class="col-md-5">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <a href="{{ route('categories.edit', $category) }}" class="badge bg-warning text-dark"> <span
                                        data-feather="edit-3"></span>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="post" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="badge bg-danger text-white border-0" onclick="return confirm('Kamu yakin?')"> <span
                                            data-feather="x-circle"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">Data category kamu belum ada karena kamu belum buat :v</div>
            @endif
        </div>
    </main>
</x-dashboard-layout>