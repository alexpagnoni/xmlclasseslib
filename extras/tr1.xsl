<?xml version="1.0" encoding="utf-8"?> 
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="xml" encoding="utf-8" />
  
  <xsl:template match="name/text()">
      <b>
      <xsl:value-of select='translate(string(.),"abcdefghijklmnopqrstuvwxyz","ABCDEFGHIJKLMNOPQRSTUVWXYZ")' />
      <xsl:apply-templates />
      </b>
  </xsl:template>
  
  <xsl:template match="@*|node()">
    <xsl:copy>
      <xsl:apply-templates select="@*|node()" />
    </xsl:copy>
  </xsl:template>
</xsl:stylesheet>
