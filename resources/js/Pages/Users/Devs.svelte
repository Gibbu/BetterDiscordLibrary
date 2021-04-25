<script>
  import {InertiaLink} from '@inertiajs/inertia-svelte';

  import Layout from '$layout/Layout.svelte';

  // State
  export let devs;
</script>

<Layout title="Developers">
  <header class="bg-gray-50 dark:bg-gray-950 border-gray-300 dark:border-gray-800 py-12 border-b flex items-center">
    <div class="wrap">
      <h1 class="text-gray-800 dark:text-white text-4xl font-display capitalize">Developers</h1>
    </div>
  </header>
  <div class="wrap">
    <div class="grid grid-cols-6 gap-4 mt-12 mb-24">
      {#each devs as dev}
        <InertiaLink href="/users/{dev.slug}" class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden transform transition hover:-translate-y-2 hover:shadow-xl">
          <img src={dev.avatar} alt="Developer avatar" class="w-full">
          <div class="p-4">
            <h3 class="font-display truncate">{dev.name}</h3>
          </div>
        </InertiaLink>
      {/each}
    </div>
  </div>
</Layout>