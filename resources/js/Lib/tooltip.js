import tippy from 'tippy.js';
import 'tippy.js/animations/shift-away-subtle.css';
import 'tippy.js/themes/light.css';
import 'tippy.js/dist/tippy.css';

export default function (node, props) {
  tippy(node, {
    allowHTML: true,
    placement: 'top',
    theme: 'light',
    appendTo: document.querySelector('#layers'),
    animation: 'shift-away-subtle',
    arrow: true,
    ...props
  });
}