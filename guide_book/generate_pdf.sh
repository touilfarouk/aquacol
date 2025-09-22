#!/bin/bash

# Create a temporary file without emojis
sed 's/[^\x00-\x7F]//g' form_development_guide.md > form_development_guide_no_emoji.md

# Generate PDF using pandoc with a custom LaTeX template
pandoc \
  --pdf-engine=xelatex \
  -V mainfont="Arial" \
  -V geometry:margin=1in \
  -V urlcolor=blue \
  -V linkcolor=blue \
  -V toccolor=blue \
  -V colorlinks=true \
  -V documentclass=article \
  -V papersize=a4 \
  -V fontsize=12pt \
  -f markdown+smart \
  --toc \
  --toc-depth=2 \
  -o form_development_guide.pdf \
  form_development_guide_no_emoji.md

# Clean up
rm form_development_guide_no_emoji.md

echo "PDF generated successfully: form_development_guide.pdf"
