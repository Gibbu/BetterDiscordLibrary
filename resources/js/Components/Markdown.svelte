<script>
  import marked from 'marked';
  import prism from 'prismjs';
  import purify from 'dompurify';

  export let string;
  export let inline = false;

  marked.setOptions({
    highlight: function(code, lang) {
      return prism.highlight(code, prism.languages[lang], lang);
    },
    sanitizer(html) {
      return purify.sanitize(html);
    }
  });
</script>

<div class="prose prose-teal dark:prose-light w-full max-w-none">
  {#if inline}
    {@html marked.parseInline(string)}
  {:else}
    {@html marked(string)}
  {/if}
</div>