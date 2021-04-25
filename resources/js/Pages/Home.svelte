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
  <section class="mt-12 mb-32">
    <header class="mb-8 flex items-center justify-between">
      <h1 class="flex items-center font-display text-gray-800 dark:text-white text-2xl">
        <Icon src={ColorSwatch} class="w-8 h-8 mr-4" /> Newest Themes
      </h1>
      <InertiaLink href="/themes" class="btn btn-primary btn-small bg-teal-500 hover:bg-teal-400 focus:bg-teal-400 focus:ring-teal-500 focus:ring-opacity-40">
        View all Themes
      </InertiaLink>
    </header>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
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
      <InertiaLink href="/plugins" class="btn btn-primary btn-small bg-teal-500 hover:bg-teal-400 focus:bg-teal-400 focus:ring-teal-500 focus:ring-opacity-40">
        View all Plugins
      </InertiaLink>
    </header>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
      {#each plugins as addon}
        <Card {addon} />
      {/each}
    </div>
  </section>
</Layout>