<script>
  export let images;

  let selected = 0;
</script>

<div class="relative">
  <div class="flex flex-col items-end">
    <div class="flex w-full items-center justify-center aspect-w-16 aspect-h-9 rounded overflow-hidden bg-gray-300 dark:bg-gray-950">
      <img src={images[selected] || '/images/notFound.png'} alt="Selected preview" class="object-contain">
    </div>
    <a href={images[selected]} target="_blank" class="text-xs text-gray-500 dark:text-gray-400">View original</a>
  </div>
  {#if images.length > 1}
    <div class="grid grid-cols-6 gap-2 mt-2">
      {#each images as src, i}
        <button
          class="bg-gray-300 dark:bg-gray-950 flex items-center justify-center aspect-w-16 aspect-h-9 rounded overflow-hidden cursor-pointer focus:outline-none {selected === i ? 'pointer-events-none ring-2 ring-teal-500' : ''}"
          on:click={() => selected = i}
        >
          <img {src} alt="Preview thumbnail" class="object-contain pointer-events-none select-none {selected === i ? 'opacity-40' : ''}">
        </button>
      {/each}
    </div>
  {/if}
</div>