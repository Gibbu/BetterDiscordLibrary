<script>
  import {createEventDispatcher} from 'svelte';
  import Portal from 'svelte-portal';
  import {fade, fly} from 'svelte/transition';
  const dispatch = createEventDispatcher();

  import Spinner from '../Spinner.svelte';

  export let visible = false;
  export let title;
  export let submitting = false;

  const close = () => {
    dispatch('close');
  }
  const confirm = () => {
    dispatch('confirm');
  }
</script>

{#if visible}
  <Portal target="#layers">
    <div class="bg-black bg-opacity-60 fixed inset-0 z-10" transition:fade={{duration: 200}} on:click={close}></div>
    <div
      class="bg-gray-200 dark:bg-gray-900 fixed top-[5vh] left-1/2 transform -translate-x-1/2 rounded shadow-xl overflow-hidden z-20 w-[500px]"
      in:fly={{y: 15, duration: 200}}
      out:fly={{y: -15, duration: 200}}
    >
      <header class="p-4">
        <h2 class="text-xl text-gray-800 dark:text-gray-100 font-display">{title}</h2>
      </header>
      {#if $$slots.default}
        <div class="px-4 pb-4">
          <slot />
        </div>
      {/if}
      <footer class="border-t border-gray-300 dark:border-gray-800 p-4 flex justify-end">
        <button type="button" class="btn btn-secondary mr-4" on:click={close}>
          Close
        </button>
        <button
          type="button"
          class="btn btn-primary bg-green-500 hover:bg-green-400 focus:bg-green-400 focus:ring-green-500 focus:ring-opacity-30"
          class:pointer-events-none={submitting}
          on:click={confirm}
        >
          {#if submitting}
            <Spinner class="mr-2" />
          {/if}
          Yes, I'm sure
        </button>
      </footer>
    </div>
  </Portal>
{/if}