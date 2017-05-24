<aside class="main-sidebar">

    <section class="sidebar">

        <ul class="sidebar-menu">

            @foreach(config('nadminpanel-modules.modules') as $module)
                @include($module)
            @endforeach



        </ul>
    </section>

</aside>
