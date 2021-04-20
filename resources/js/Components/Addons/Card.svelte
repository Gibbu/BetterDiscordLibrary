<script>
  import {InertiaLink} from '@inertiajs/inertia-svelte';
  import dayjs from 'dayjs';
  import advancedFormat from 'dayjs/plugin/advancedFormat'

  dayjs.extend(advancedFormat);

  export let addon;
</script>

<InertiaLink href="/{addon.type}s/{addon.name}" class="rounded overflow-hidden transform transition bg-gray-50 dark:bg-gray-800 hover:-translate-y-2 hover:shadow-xl">
  <div class="aspect-w-16 aspect-h-9">
    <img src={addon.thumbnail || addon.images[0]} loading="lazy" alt="Addon preview" class="object-cover w-full">
  </div>
  <div class="p-4">
    <h2 class="text-gray-800 dark:text-gray-100 text-xl font-display">{addon.name}</h2>
    <p class="truncate">{addon.description}</p>
  </div>
  {#if addon.user}
    <footer class="p-4 -mt-4 flex items-center">
      <img src={addon.user.avatar} alt="Developer avatar" class="w-10 h-10 rounded-full">
      <div class="ml-4">
        <p class="text-gray-800 dark:text-gray-100">{addon.user.name}</p>
        <small class="block -mt-0.5 text-sm text-gray-500 dark:text-gray-500">{dayjs(addon.release).format('Do MMM, YYYY')}</small>
      </div>
    </footer>
  {/if}
</InertiaLink>