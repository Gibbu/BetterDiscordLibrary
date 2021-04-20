<script>
  import {page} from '@inertiajs/inertia-svelte';
  import {fly} from 'svelte/transition';
  import Icon, {CheckCircle, ExclamationCircle, X} from 'svelte-hero-icons';

  $: flash = $page.props.flash;

  const flashIcons = {
    success: CheckCircle,
    error: ExclamationCircle
  }
  const hideFlash = () => flash = null;
  $: if (flash) {
    setTimeout(() => flash = null, 15000)
  }
</script>

{#if flash}
  <div 
    class="bg-white dark:bg-gray-800 rounded overflow-hidden shadow-xl flex items-stretch fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50"
    in:fly={{y: 15, duration: 200}}
    out:fly={{y: -15, duration: 200}}
  >
    <div class="w-14 flex {flash.type === 'success' ? 'bg-green-500' : 'bg-red-500'}">
      <Icon src={flashIcons[flash.type]} class="w-8 h-8 m-auto text-white" />
    </div>
    <p class="py-4 px-5 {flash.type === 'success' ? 'text-green-500' : 'text-red-500'}">{flash.message}</p>
    <button type="button" class="px-4 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none" on:click={hideFlash}>
      <Icon src={X} class="w-4 h-4" />
    </button>
  </div>
{/if}