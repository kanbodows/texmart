@extends("frontend.layouts.app")

@section("title")
    {{ app_name() }}
@endsection

@section("content")
    <section class="bg-white dark:bg-gray-800">
        <div class="mx-auto max-w-screen-xl px-4 py-24 text-center sm:px-12">
            <h1
                class="mb-6 text-4xl font-extrabold leading-none tracking-tight text-gray-900 dark:text-white sm:text-6xl"
            >
            Швейный рынок22 <br>
            Кыргызстана онлайн
            </h1>
            <p class="mb-10 text-lg font-normal text-gray-500 dark:text-gray-400 sm:px-16 sm:text-2xl xl:px-48">
                портал по поиску и размещению заказов на производство <br>
                одежды в швейных фабриках и оптовых покупок
            </p>
            @include("frontend.includes.messages")
        </div>
    </section>
@endsection
