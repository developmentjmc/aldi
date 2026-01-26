
@if (isset($title) || !empty($breadcrumbs))
	<div class="d-flex justify-content-between pt-3 pb-3 px-3">
		<div class="fs-4">
			{{ $title ?? '' }}
		</div>

		<ol class="breadcrumb p-0 m-0">
			@foreach (($breadcrumbs ?? []) as $breadcrumb)
				<li class="breadcrumb-item">
					{{ $breadcrumb }}
				</li>
			@endforeach
		</ol>
	</div>
@endif

<div class="px-3 pb-3">
	@yield('content')
</div>
