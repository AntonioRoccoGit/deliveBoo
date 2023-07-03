@extends('layouts.app')
@section('content')
    <div class="container w-50">
      @if ($errors->any())
      <p>attenzione controlla errori</p>  
  @endif

        <form class="mt-4" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">nome prodotto</label>
                <input type="text"class="form-control @error('name')is-invalid @enderror" id="name" name="name" value="{{old('name')}}">
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            </div>
           
            <select class="form-select" aria-label="Default select example" name="category_id">
                <option selected>Seleziona una categoria</option>
                @foreach ($categories as $category)
              
                    <option value="{{ $category->id }}" @selected(old("category_id") == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <div class="mb-3">
                <label for="price" class="form-label">Inserisci il prezzo</label>
                <input type="number" class="form-control @error('price')is-invalid @enderror" id="price" name="price" step="0.01" min="1"
                    value="{{old('price')}}">
                    @error('price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    
                @enderror
            </div>
            <div class="mb-3">
                <label for="image">Inserisci img</label>
                <input class="form-control @error('image')is-invalid @enderror" type="file"name="image" id="image" value="{{old('image')}}">
                @error('image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Inserisci descrizione del prodotto</label>
                <textarea class="form-control @error('description')is-invalid @enderror" id="description" name="description" value="" rows="3">{{old('description')}}</textarea>
                @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
            </div>
            <button class="btn btn-primary" type="submit">Salva prodotto</button>

        </form>
    </div>
@endsection