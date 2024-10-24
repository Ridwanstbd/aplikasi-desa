<h3 class="text-center py-2 my-3 w-100 text-white" style="background-color: #2a401a;">MARKETPLACE</h3>
<div class="d-flex flex-wrap justify-content-center gap-2 my-2">
    @foreach ($marketplaces as $marketplace)
        <div class="mb-2">
            <x-marketplace-button type="{{$marketplace->type}}" url="{{$marketplace->marketplace_url}}" name="{{$marketplace->name}}" />
        </div>
    @endforeach
</div>
