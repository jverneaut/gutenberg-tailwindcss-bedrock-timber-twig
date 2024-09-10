const path = require('path');

module.exports = {
  defaultValues: {
    transformer: (view) => {
      delete view.style;
      delete view.editorStyle;

      return view;
    },
    render: 'file:./render.twig',
  },
  blockTemplatesPath: path.join(__dirname, 'files/block'),
};
