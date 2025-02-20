@extends("admin.layouts.app")

@section("title")
    @lang("Dashboard")
@endsection

@section("breadcrumbs")
    <x-admin.breadcrumbs />
@endsection

@section("content")
    <div class="card mb-4">
        <div class="card-body">
            <x-admin.section-header>
                @lang("Admin Dashboard")

                <x-slot name="toolbar">
                    <button
                        class="btn btn-outline-primary mb-1"
                        type="button"
                        data-toggle="tooltip"
                        data-coreui-placement="top"
                        title="Tooltip"
                    >
                        <i class="fa-solid fa-bullhorn"></i>
                    </button>
                </x-slot>
            </x-admin.section-header>

            <!-- Dashboard Content Area -->

            <!-- / Dashboard Content Area -->
        </div>
    </div>

    {{-- Demo content --}}
    @include("admin.includes.dashboard_demo_data")
@endsection
