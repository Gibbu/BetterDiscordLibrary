<script>
  import {onMount, tick} from 'svelte';
  import clickOutside from '$lib/clickOutside';

  export let btn = '';
  export let menu = '';
  export let container = '';

  let visible = false;
  let trigger;
  let menuSelector;
  
  $: if (menuSelector) {
    menuSelector.querySelectorAll('button, a').forEach(el => {
      el.addEventListener('click', () => {
        visible = false;
      })
    })
  }
</script>

<div class="relative {container}">
  <button bind:this={trigger} class={btn} on:click={() => visible = !visible}>
    <slot />
  </button>

  {#if visible}
    <div
      bind:this={menuSelector}
      class="absolute top-full w-[200px] transform border translate-y-3 rounded p-2 z-10 shadow-lg border-gray-300 bg-white dark:border-gray-800 dark:bg-gray-950 {menu}"
      use:clickOutside={trigger}
      on:clickedOutside={() => visible = false}
    >
      <slot name="menu" />
    </div>
  {/if}
</div>