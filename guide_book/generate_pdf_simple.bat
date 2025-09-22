@echo off
echo Generating PDF...

:: Generate PDF using pandoc with the emoji-free markdown file
pandoc ^
  --pdf-engine=xelatex ^
  -V mainfont="Arial" ^
  -V geometry:margin=1in ^
  -V urlcolor=blue ^
  -V linkcolor=blue ^
  -V toccolor=blue ^
  -V colorlinks=true ^
  -V documentclass=article ^
  -V papersize=a4 ^
  -V fontsize=12pt ^
  -f markdown+smart ^
  --toc ^
  --toc-depth=2 ^
  -o form_development_guide.pdf ^
  form_development_guide_pdf.md

echo.
echo PDF generated successfully: form_development_guide.pdf
pause
