<h3 class="text-center pt-5 pb-3">MARKETPLACE</h3>
<div class="d-flex flex-wrap justify-content-center gap-2 my-2">
    @foreach ($marketplaces as $marketplace)
        <div class="mb-2">
            <x-marketplace-button type="{{$marketplace->type}}" url="{{$marketplace->marketplace_url}}" name="{{$marketplace->name}}" />
        </div>
    @endforeach
</div>
