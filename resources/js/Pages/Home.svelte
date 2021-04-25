<script>
  import Icon, {ColorSwatch, Puzzle} from 'svelte-hero-icons';
  import {InertiaLink} from '@inertiajs/inertia-svelte';

  import Layout from '$layout/Layout.svelte';

  import Card from '$components/Addons/Card.svelte';

  // State
  export let themes;
  export let plugins;
</script>

<Layout>
  <div class="wrap">
    <section class="mt-12 mb-32">
      <header class="mb-8 flex items-center justify-between">
        <h1 class="flex items-center font-display text-gray-800 dark:text-white text-2xl">
          <Icon src={ColorSwatch} class="w-8 h-8 mr-4" /> Newest Themes
        </h1>
        <InertiaLink href="/themes" class="btn btn-secondary">
          View all Themes
        </InertiaLink>
      </header>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {#each themes as addon}
          <Card {addon} />
        {/each}
      </div>
    </section>
    <section class="mt-12">
      <header class="mb-8 flex items-center justify-between">
        <h1 class="flex items-center font-display text-gray-800 dark:text-white text-2xl">
          <Icon src={Puzzle} class="w-8 h-8 mr-4" /> Newest Plugins
        </h1>
        <InertiaLink href="/plugins" class="btn btn-secondary">
          View all Plugins
        </InertiaLink>
      </header>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {#each plugins as addon}
          <Card {addon} />
        {/each}
      </div>
    </section>
  </div>
</Layout>