<script>
  import axios from 'axios';
  import {InertiaLink} from '@inertiajs/inertia-svelte';
  import tooltip from '$lib/tooltip';
  import Icon, {Download, Eye, Heart} from 'svelte-hero-icons';
  import HRN from 'human-readable-numbers';
  import dayjs from 'dayjs';
  import advancedFormat from 'dayjs/plugin/advancedFormat'

  dayjs.extend(advancedFormat);

  export let addon;

  const download = () => {
    axios.put(`/download/${addon.id}`);
  }
</script>

<div>
  <header class="block relative bg-gray-50 dark:bg-gray-800 rounded overflow-hidden">
    <InertiaLink href="/{addon.type}s/{addon.name}" class="block aspect-w-16 aspect-h-9">
      <img src={addon.thumbnail || addon.images[0]} loading="lazy" alt="Addon preview" class="object-cover w-full">
    </InertiaLink>
    <div class="absolute bottom-0 w-full p-4 flex items-center justify-between bg-gradient-to-b from-transparent to-black pointer-events-none">
      <h2 class="font-display text-white text-xl">{addon.name}</h2>
      <a
        href={addon.download}
        target="_blank"
        class="bg-white bg-opacity-10 text-white p-2 rounded pointer-events-auto hover:bg-opacity-100 hover:text-black"
        use:tooltip={{content: 'Download', delay: [250, 0]}}
        on:click={download}
      >
        <Icon src={Download} class="w-5 h-5" />
      </a>
    </div>
  </header>
  <footer class="flex items-center justify-between mt-2">
    {#if addon.user}
      <div class="flex items-center">
        <InertiaLink href="/users/{addon.user.slug}" class="mr-2">
          <img src={addon.user.avatar} alt="User avatar" class="rounded-full w-8 h-8">
        </InertiaLink>
        <InertiaLink href="/users/{addon.user.slug}" class="truncate max-w-[200px]">{addon.user.name}</InertiaLink>
      </div>
    {/if}
    <div class="flex items-center">
      <div class="flex items-center">
        <Icon src={Eye} class="w-5 h-5 mr-1" /> {HRN.toHumanString(addon.view_count)}
      </div>
      <div class="flex items-center ml-4">
        <Icon src={Download} class="w-5 h-5 mr-1" /> {HRN.toHumanString(addon.download_count)}
      </div>
      <div class="flex items-center ml-4">
        <Icon src={Heart} class="w-5 h-5 mr-1" /> {HRN.toHumanString(addon.likes_count)}
      </div>
    </div>
  </footer>
</div>