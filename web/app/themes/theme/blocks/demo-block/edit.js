import { useBlockProps } from '@wordpress/block-editor';
import { RichText } from '@wordpress/block-editor';

export default ({ attributes, setAttributes }) => {
  return (
    <section {...useBlockProps({ className: 'p-8' })}>
      <RichText
        className="text-2xl font-semibold"
        value={attributes.content}
        onChange={(content) => setAttributes({ content })}
      />
    </section>
  );
};
