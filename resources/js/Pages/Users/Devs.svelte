<script>
  import {InertiaLink} from '@inertiajs/inertia-svelte';

  import Layout from '$layout/Layout.svelte';

  // State
  export let devs;
</script>

<Layout title="Developers">
  <header class="border-gray-300 dark:border-gray-800 my-12 pb-12 border-b flex items-center justify-between">
    <h1 class="text-gray-800 dark:text-white text-4xl font-display capitalize">Developers</h1>
  </header>
  <div class="grid grid-cols-6 gap-4 mt-12 mb-24">
    {#each devs as dev}
      <InertiaLink href="/users/{dev.name}/{dev.discrim}" class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden transform transition hover:-translate-y-2 hover:shadow-xl">
        <img src={dev.avatar} alt="Developer avatar" class="w-full">
        <div class="p-4">
          <h3 class="font-display truncate">{dev.name}</h3>
        </div>
      </InertiaLink>
    {/each}
  </div>
</Layout>