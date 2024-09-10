const path = require('path');

module.exports = {
  defaultValues: {
    transformer: (view) => {
      delete view.style;
      delete view.editorStyle;

      return {
        ...view,
        supports: {
          html: false,
          align: ['full'],
        },
        attributes: {
          align: {
            type: 'string',
            default: 'full',
          },
        },
      };
    },
    render: 'file:./render.twig',
  },
  blockTemplatesPath: path.join(__dirname, 'files/block'),
};
