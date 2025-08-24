<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $size }}">
        <div class="modal-content custom-modal p-4">
            <form action="{{ $action }}" method="{{ strtolower($method) === 'get' ? 'GET' : 'POST' }}"
                @if(strtoupper($method)==='PUT' ) onsubmit="return confirm('Are you sure?');" @endif>
                @csrf
                @if(!in_array(strtoupper($method), ['GET', 'POST']))
                @method($method)
                @endif

                <h5 class="mb-3 fw-bold">{{ $title }}</h5>

                {{-- Form fields dikirim melalui slot --}}
                {{ $slot }}

            </form>
        </div>
    </div>
</div>