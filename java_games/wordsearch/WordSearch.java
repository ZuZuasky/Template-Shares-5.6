/************************************************************************************************

WordSearch.java

<applet code="WordSearch.class" width=w height=h>
<param name="screencolors" value="foreground,background,border">
<param name="buttoncolors" value="foreground,background">
<param name="gridcolors" value="foreground,background,found,highlight">
<param name="listcolors" value="foreground,background,found">
<param name="screenfont" value="name,style,size">
<param name="buttonfont" value="name,style,size">
<param name="gridfont" value="name,style,size">
<param name="listfont" value="name,style,size">
<param name="gridsize" value="rows,cols,size">
<param name="files" value="url,url,url...">
</applet>

************************************************************************************************/

import java.awt.*;
import java.io.*;
import java.net.*;
import java.util.*;
import java.applet.Applet;

/************************************************************************************************
  The WSWord class defines data for a single word.
************************************************************************************************/

class WSWord {

  // Fields:

  public String  list   = null;    // Text for word list.
  public String  grid   = null;    // Text for grid.
  public Point   endpt1 = null;    // Starting grid location.
  public Point   endpt2 = null;    // Ending grid location.
  public boolean found  = false;   // Found flag.

  // Constructors:

  public WSWord(String s) {

    String t;
    int i;
    char c;

    this.list = s.toUpperCase();
    t = new String();
    for (i = 0; i < s.length(); i++) {
      c = s.charAt(i);
      if (Character.isLetter(c))
        t += c;
    }
    this.grid = t.toUpperCase();
  }
}

/************************************************************************************************
  The WSButton class defines a button.   
************************************************************************************************/

class WSButton {

  // Fields:

  public int x, y;             // Screen position.
  public int width, height;    // Width and height.

  private String  text;       // Text, if a text button.
  private Font    font;       // Font for text.
  private Polygon polygon;    // Shape if a graphical button.

  private Color fgColor = Color.black;        // Foreground color.
  private Color bgColor = Color.lightGray;    // Background color.
  private Color bdColor = Color.black;        // Border color.

  // Constructors:

  public WSButton(String s, Font font, int width, int height) {

    // Text button.

    this.text = s;
    this.font = font;
    this.x = 0;
    this.y = 0;
    this.width = width;
    this.height = height;
  }

  public WSButton(Polygon p, int width, int height) {

    // Graphical button.

    this.polygon = p;
    this.x = 0;
    this.y = 0;
    this.width = width;
    this.height = height;
  }

  public void setColors(Color fg, Color bg, Color bd) {

    this.fgColor = fg;
    this.bgColor = bg;
    this.bdColor = bd;
  }

  public boolean inside(int x, int y) {

    // Determine if point x, y is on the button.

    if (x > this.x && x < this.x + this.width &&
        y > this.y && y < this.y + this.height)
      return true;
   return false;
  }

  public void draw(Graphics g) {

    FontMetrics fm;
    int x, y;

    // Color background and draw border.

    g.setColor(this.bgColor);
    g.fillRect(this.x, this.y, this.width, this.height);
    g.setColor(this.bdColor);
    g.drawRect(this.x, this.y, this.width - 1, this.height - 1);

    // Write text or draw shape.

    g.setColor(this.fgColor);
    if (this.text != null) {
      g.setFont(this.font);
      fm = g.getFontMetrics();
      x = this.x + (this.width - fm.stringWidth(this.text)) / 2;
      y = this.y + (this.height + fm.getAscent()) / 2;
      g.drawString(this.text, x, y);
    }
    if (this.polygon != null) {
      x = this.x + this.width / 2;
      y = this.y + this.height / 2;
      g.translate(x, y);
      g.fillPolygon(this.polygon);
      g.translate(-x, -y);
    }
  }
}

/************************************************************************************************
  The WSGrid class defines the grid canvas.
************************************************************************************************/

class WSGrid {

  // Constants.

  static final String LETTERS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  static final int    MAX     = 100;

  // Fields.

  public int x, y;             // Screen position.
  public int width, height;    // Width and height.

  public boolean select;            // Select flag.
  public int     startX, startY;    // Selection endpoints.
  public int     endX, endY;
  public int     size;              // Grid cell size.

  private int         rows, cols;    // Grid size.
  private Font        font;          // Font for letters.
  private FontMetrics fm;            // Font metrics.

  private Color fgColor = Color.black;    // Foreground color.
  private Color bgColor = Color.white;    // Background color.
  private Color bdColor = Color.black;    // Border color.
  private Color fdColor = Color.black;    // Found word color. 
  private Color hiColor = Color.black;    // Highlighted word color.

  private char[][] grid;    // Letter grid.

  // Constructor.

  public WSGrid(int rows, int cols, int size, Font font) {

    this.x = 0;
    this.y = 0;
    this.rows = rows;
    this.cols = cols;
    this.font = font;
    this.size = size;
    this.select = false;

    // Determine width and height based on rows, cols and font.

    this.width = cols * this.size;
    this.height = rows * this.size;
 }

  public void setColors(Color fg, Color bg, Color bd, Color fd, Color hi) {

    this.fgColor = fg;
    this.bgColor = bg;
    this.bdColor = bd;
    this.fdColor = fd;
    this.hiColor = hi;
  }

  public void clear() {

    int i, j;

    this.grid = new char[this.rows][this.cols];
    for (i = 0; i < this.rows; i++)
      for (j = 0; j < this.cols; j++)
        grid[i][j] = ' ';
  }

  public boolean inside(int x, int y) {

    // Determine if point x, y is on the button.

    if (x > this.x && x < this.x + this.width &&
        y > this.y && y < this.y + this.height)
      return true;
   return false;
  }

  public void fill() {

    WSWord ws;
    int i, j, row, col, r, c;
    boolean added;

    // Add each word randomly to the grid.

    this.clear();
    for (i = 0; i < WordSearch.words.size(); i++) {

      // Get word and pick a random starting position.

      ws = (WSWord) WordSearch.words.elementAt(i);
      row = (int) (Math.random() * this.rows);
      col = (int) (Math.random() * this.cols);
      added = false;

      // If word is too long to fit grid, remove it. Otherwise try adding it to
      // each cell until we succeed or exhaust all possibilities.

      if (ws.grid.length() <= Math.max(this.rows, this.cols)) {
        r = row;
        c = col;
        do {
          added = addWord(ws, r, c);
          if (!added)
            if (++c > this.cols - 1) {
              c = 0;
              if (++r > this.rows - 1)
                r = 0;
            }
        } while (!added && !(r == row && c == col));
      }

      // If we couldn't fit it in, remove it from the list.

      if (!added) {
        WordSearch.words.removeElementAt(i);
        i -= 1;
      }
    }

    // Fill in rest of grid with random letters.

    for (i = 0; i < this.rows; i++)
      for (j = 0; j < this.cols; j++)
        if (grid[i][j] == ' ') {
          r = (int) (Math.random() * LETTERS.length());
          grid[i][j] = LETTERS.charAt(r);
        }
  }

  private boolean addWord(WSWord ws, int r1, int c1) {

    int i, r2, c2, dx, dy, count;
    boolean fit;
    char c;

    // Get a random direction to start with.

    dy = (int) (Math.random() * 3) - 1;
    dx = (int) (Math.random() * 3) - 1;
    count = 0;

    // Try each direction until we find a fit or exhaust all possibilities.

    do {
      fit = true;
      r2 = r1 + (ws.grid.length() - 1) * dy;
      c2 = c1 + (ws.grid.length() - 1) * dx;
      if (!(dx == 00 && dy == 0) &&
          r2 >= 0 && r2 < this.rows && c2 >= 0 && c2 < this.cols)
        for (i = 0; i < ws.grid.length(); i++) {
          c = grid[r1 + i * dy][c1 + i * dx];
          if (c != ' ' && c != ws.grid.charAt(i))
            fit = false;
        }
      else
        fit = false;
      if (!fit)
        if (++dx > 1) {
          dx = -1;
          if (++dy > 1)
            dy = -1;
        }
      count++;
    } while (!fit && count <= 8);

    // If we found a fit, add word and save endpoints.

    if (fit) {
      for (i = 0; i < ws.grid.length(); i++)
        this.grid[r1 + i * dy][c1 + i * dx] = ws.grid.charAt(i);
      ws.endpt1 = new Point(c1, r1);
      ws.endpt2 = new Point(c2, r2);
    }

    return fit;
  }

  public boolean checkSelection() {

    WSWord ws;
    int x1, y1, x2, y2;
    Point p1, p2;
    int i;

    // Check selection against word list. If endpoints match, mark it as found.

    x1 = Math.min(Math.max(this.startX / this.size, 0), this.cols - 1);
    y1 = Math.min(Math.max(this.startY / this.size, 0), this.rows - 1);
    x2 = Math.min(Math.max(this.endX / this.size, 0), this.cols - 1);
    y2 = Math.min(Math.max(this.endY / this.size, 0), this.rows - 1);
    p1 = new Point(x1, y1);
    p2 = new Point(x2, y2);
    for (i = 0; i < WordSearch.words.size(); i++) {
      ws = (WSWord) WordSearch.words.elementAt(i);
      if ((ws.endpt1.equals(p1) && ws.endpt2.equals(p2)) ||
          (ws.endpt1.equals(p2) && ws.endpt2.equals(p1))) {
        ws.found = true;
        return true;
      }
    }
    return false;
  }

  public void draw(Graphics g) {

    WSWord ws;
    int i, j;
    int k, l;
    int x, y;
    int x1, y1, x2, y2;
    Polygon p;
    Character c;
    FontMetrics fm;

    // Color background and draw border.

    g.setColor(this.bgColor);
    g.fillRect(this.x, this.y, this.width, this.height);
    g.setColor(this.bdColor);
    g.drawRect(this.x, this.y, this.width - 1, this.height - 1);

    // Shade found words.

    g.setColor(this.fdColor);
    for (i = 0; i < WordSearch.words.size(); i++) {
      ws = (WSWord) WordSearch.words.elementAt(i);
      if (ws.found) {
        x1 = this.x + this.size * ws.endpt1.x + this.size / 2;
        y1 = this.y + this.size * ws.endpt1.y + this.size / 2;
        x2 = this.x + this.size * ws.endpt2.x + this.size / 2;
        y2 = this.y + this.size * ws.endpt2.y + this.size / 2;
        p = getPolygon(x1, y1, x2, y2);
        g.fillPolygon(p);
      }
    }

    // Shade and circle current selection, if any.

    if (WordSearch.words.size() > 0 && this.select) {
      i = Math.min(Math.max(this.startY / this.size, 0), this.rows - 1);
      j = Math.min(Math.max(this.startX / this.size, 0), this.cols - 1);
      k = Math.min(Math.max(this.endY / this.size, 0), this.rows - 1);
      l = Math.min(Math.max(this.endX / this.size, 0), this.cols - 1);
      x1 = this.size * j + this.size / 2;
      y1 = this.size * i + this.size / 2;
      x2 = this.size * l + this.size / 2;
      y2 = this.size * k + this.size / 2;
      p = getPolygon(x1, y1, x2, y2);
      g.setColor(this.hiColor);
      g.fillPolygon(p);
      g.setColor(this.fgColor);
      g.drawPolygon(p);
      g.drawLine(p.xpoints[p.npoints - 1], p.ypoints[p.npoints - 1], p.xpoints[0], p.ypoints[0]);
    }

    // Write letters.

    g.setFont(this.font);
    g.setColor(this.fgColor);
    fm = g.getFontMetrics();
    for (i = 0; i < this.rows; i++)
      for (j = 0; j < this.cols; j++) {
        c = new Character(this.grid[i][j]);
        x = this.x + j * this.size + (this.size - fm.stringWidth(c.toString())) / 2;
        y = this.y + i * this.size + (this.size - fm.getHeight()) / 2 + fm.getAscent();
        g.drawString(c.toString(), x, y);
      }

    // Circle found words.

    g.setColor(this.fgColor);
    for (i = 0; i < WordSearch.words.size(); i++) {
      ws = (WSWord) WordSearch.words.elementAt(i);
      if (ws.found) {
        x1 = this.x + this.size * ws.endpt1.x + this.size / 2;
        y1 = this.y + this.size * ws.endpt1.y + this.size / 2;
        x2 = this.x + this.size * ws.endpt2.x + this.size / 2;
        y2 = this.y + this.size * ws.endpt2.y + this.size / 2;
        p = getPolygon(x1, y1, x2, y2);
        g.drawPolygon(p);
        g.drawLine(p.xpoints[p.npoints - 1], p.ypoints[p.npoints - 1], p.xpoints[0], p.ypoints[0]);
      }
    }
  }

  private Polygon getPolygon(int x1, int y1, int x2, int y2) {

    Polygon p;
    double dx, dy, a, b, angle;
    int r;

    // Find angles.

    dx = x2 - x1;
    dy = y2 - y1;
    a = 0;
    if (dx == 0) {
      if (dy < 0)
        a = Math.PI / 2;
      else if (dy > 0)
        a = 3 * Math.PI / 2;
        
    }
    else if (dy == 0) {
      if (dx > 0)
        a = 0;
      else
        a = Math.PI;
    }
    else {
      a = Math.atan(Math.abs(dy / dx));
      if (dx < 0 && dy < 0)
        a = Math.PI - a;
      if (dx < 0 && dy > 0)
        a += Math.PI;
      if (dx > 0 && dy > 0)
        a = 2 * Math.PI - a;
    }
    a = a + Math.PI / 2;
    b = a + Math.PI;

    // Build polygon as a half circle around each endpoint and connect them.

    p = new Polygon();
    r = (int) Math.round(this.size * Math.sin(Math.PI / 4) / 2);
    for (angle = a; angle < a + Math.PI; angle += Math.PI / 30)
      p.addPoint((int) Math.round(x1 + r * Math.cos(angle)),
                 (int) Math.round(y1 - r * Math.sin(angle)));
    p.addPoint((int) Math.round(x1 + r * Math.cos(b)),
               (int) Math.round(y1 - r * Math.sin(b)));
    for (angle = b; angle < b + Math.PI; angle += Math.PI / 30)
      p.addPoint((int) Math.round(x2 + r * Math.cos(angle)),
                 (int) Math.round(y2 - r * Math.sin(angle)));
    p.addPoint((int) Math.round(x2 + r * Math.cos(a)),
               (int) Math.round(y2 - r * Math.sin(a)));
    return p;
  }
}

/************************************************************************************************
  The WSList class defines the word list.   
************************************************************************************************/

class WSList {

  // Fields.

  public int x, y;             // Screen position.
  public int width, height;    // Width and height.
  public int scroll;           // Starting word for list display.

  private Font        font;    // Font for words.
  private FontMetrics fm;      // Font metrics.

  private Color fgColor = Color.black;       // Foreground color.
  private Color bgColor = Color.white;       // Background color.
  private Color bdColor = Color.black;       // Border color.
  private Color fdColor = Color.darkGray;    // Found word color.

  // Constructor.

  public WSList(int width, int height, Font font) {

    this.x = 0;
    this.y = 0;
    this.width = width;
    this.height = height;
    this.scroll = 0;
    this.font = font;
 }

  public void setColors(Color fg, Color bg, Color bd, Color fd) {

    this.fgColor = fg;
    this.bgColor = bg;
    this.bdColor = bd;
    this.fdColor = fd;
  }

  public void draw(Graphics g) {

    int i;
    int x, y;
    Rectangle rect;
    FontMetrics fm;
    WSWord ws;

    // Color background.

    g.setColor(this.bgColor);
    g.fillRect(this.x, this.y, this.width, this.height);

    // Write each word in the list.

    g.setFont(this.font);
    fm = g.getFontMetrics();
    this.scroll = Math.min(Math.max(this.scroll, 0), Math.max(WordSearch.words.size() - this.height / fm.getHeight(), 0));
    x = this.x + fm.getMaxAdvance() / 2;
    y = this.y + fm.getHeight();
    for (i = this.scroll; i < WordSearch.words.size() && y < this.y + this.height; i++) {
      ws = (WSWord) WordSearch.words.elementAt(i);
      if (ws.found)
        g.setColor(this.fdColor);
      else
        g.setColor(this.fgColor);
      g.drawString(ws.list, x, y);
      y += fm.getHeight();
    }

    // Draw border.

    g.setColor(this.bdColor);
    g.drawRect(this.x, this.y, this.width - 1, this.height - 1);
  }
}

/************************************************************************************************
  Main applet code.
************************************************************************************************/

public class WordSearch extends Applet implements Runnable {

  // Thread control variables.

  Thread loopThread;    // Main thread.

  // Constants

  static final int DELAY = 50;    // Milliseconds between screen updates.

  static final int INIT  =  1;    // Game states.
  static final int PLAY  =  2;
  static final int OVER  =  3;

  // Parameters and defaults.

  Color  scrnFgColor = Color.black;             // Background color.
  Color  scrnBgColor = Color.white;             // Foreground color.
  Color  scrnBdColor = Color.black;             // Border color.   
  String scrnFontStr = "Helvetica,bold,12";     // Font.

  Color  bttnFgColor = Color.black;             // Button background color.
  Color  bttnBgColor = Color.lightGray;         // Button foreground color.
  String bttnFontStr = "Dialog,bold,10";        // Button font.

  Color  gridFgColor = Color.black;             // Grid text color.
  Color  gridBgColor = Color.white;             // Grid background color.
  Color  gridHiColor = Color.yellow;            // Grid highlight color.
  Color  gridFdColor = Color.lightGray;         // Grid found color.
  String gridFontStr = "Courier,plain,14";      // Grid font.

  Color  listFgColor = Color.black;             // List text color.
  Color  listBgColor = Color.white;             // List background color.
  Color  listFdColor = Color.lightGray;         // List found color.
  String listFontStr = "Helvetica,plain,12";    // List font.

  int    gridRows    = 15;                      // Grid rows.
  int    gridCols    = 15;                      // Grid columns.
  int    gridSize    = 20;                      // Grid cell size.

  Vector files       = new Vector();            // List if text file URLs.

  // Global variables.

  static Vector words;    // Word list.

  Font scrnFont;    // Screen font.
  Font bttnFont;    // Screen font.
  Font gridFont;    // Grid font.
  Font listFont;    // List font.

  // Display elements.

  WSGrid   grid;
  WSList   list;
  WSButton newGame;
  WSButton solveGame;
  WSButton scrollUp;
  WSButton scrollDn;

  // File data.

  int fileNum;

  // Game data.

  int gap = 4;     // Gap between display elements.

  int    gameState;    // Game state.
  int    scroll;       // Scroll direction.
  int    count;        // Number of words found.
  long   startTime;    // Start time of current game.
  String timeText;     // Elapsed time text.
  String statText;     // Words found/total text.
  String subjText;     // Word list subject, from file.

  // Values for the off-screen and scratch image.

  Dimension offDimension;
  Image offImage;
  Graphics offGraphics;

  // Applet information.

  public String getAppletInfo() {

    return("Word Search\n\nCopyright 1999 by Mike Hall");
  }

  public void init() {

    Graphics g;
    Dimension d;
    Font f;
    FontMetrics fm;
    String s;
    StringTokenizer st;
    int n;
    Polygon p;
    int x, y;
    int w, h;

    // Take credit.

    System.out.println("Word Search, Copyright 1999 by Mike Hall.");

    // Get colors.

    try {
      s = getParameter("screencolors");
      if (s != null) {
        st = new StringTokenizer(s, ",");
        scrnFgColor = getColorParm(st.nextToken());
        scrnBgColor = getColorParm(st.nextToken());
        scrnBdColor = getColorParm(st.nextToken());
      }
    }
    catch (Exception e) {}
    try {
      s = getParameter("buttoncolors");
      if (s != null) {
        st = new StringTokenizer(s, ",");
        bttnFgColor = getColorParm(st.nextToken());
        bttnBgColor = getColorParm(st.nextToken());
      }
    }
    catch (Exception e) {}
    try {
      s = getParameter("gridcolors");
      if (s != null) {
        st = new StringTokenizer(s, ",");
        gridFgColor = getColorParm(st.nextToken());
        gridBgColor = getColorParm(st.nextToken());
        gridFdColor = getColorParm(st.nextToken());
        gridHiColor = getColorParm(st.nextToken());
      }
    }
    catch (Exception e) {}
    try {
      s = getParameter("listcolors");
      if (s != null) {
        st = new StringTokenizer(s, ",");
        listFgColor = getColorParm(st.nextToken());
        listBgColor = getColorParm(st.nextToken());
        listFdColor = getColorParm(st.nextToken());
      }
    }
    catch (Exception e) {}

    // Get fonts.

    scrnFont = getFontParm(scrnFontStr);
    try {
      s = getParameter("screenfont");
      if (s != null)
        if ((f = getFontParm(s)) != null)
          scrnFont = f;
    }
    catch (Exception e) {}
    bttnFont = getFontParm(bttnFontStr);
    try {
      s = getParameter("buttonfont");
      if (s != null)
        if ((f = getFontParm(s)) != null)
          bttnFont = f;
    }
    catch (Exception e) {}
    gridFont = getFontParm(gridFontStr);
    try {
      s = getParameter("gridfont");
      if (s != null)
        if ((f = getFontParm(s)) != null)
          gridFont = f;
    }
    catch (Exception e) {}
    listFont = getFontParm(listFontStr);
    try {
      s = getParameter("listfont");
      if (s != null)
        if ((f = getFontParm(s)) != null)
          listFont = f;
    }
    catch (Exception e) {}

    // Get grid size.

    try {
      s = getParameter("gridsize");
      if (s != null) {
        st = new StringTokenizer(s, ",");
        if ((n = Integer.parseInt(st.nextToken())) > 0)
          gridRows = n;
        if ((n = Integer.parseInt(st.nextToken())) > 0)
          gridCols = n;
        if ((n = Integer.parseInt(st.nextToken())) > 0)
          gridSize = n;
      }
    }
    catch (Exception e) {}

    // Get list of word file URLs.

    try {
      s = getParameter("files");
      if (s != null) {
        st = new StringTokenizer(s, ",");
        while (st.hasMoreTokens())
          files.addElement(st.nextToken());
      }
    }
    catch (Exception e) {}

    // Get screen size and build display.

    g = getGraphics();
    d = size();

    // Create and position grid.

    grid = new WSGrid(gridRows, gridCols, gridSize, gridFont);
    grid.setColors(gridFgColor, gridBgColor, scrnBdColor, gridFdColor, gridHiColor);
    grid.clear();

    // Create and position list.

    g.setFont(listFont);
    fm = g.getFontMetrics();
    x = grid.x + grid.width + gap;
    w = d.width - x;
    h = grid.height - 2 * (fm.getHeight() + gap);
    list = new WSList(w, h, listFont);
    list.x = x;
    list.y = grid.y + fm.getHeight() + gap;
    list.setColors(listFgColor, listBgColor, scrnBdColor, listFdColor);

    // Create and position scroll buttons.

    g.setFont(bttnFont);
    w = list.width;
    h = fm.getHeight();
    p = new Polygon();
    p.addPoint(0, -h / 2 + 2);
    p.addPoint(-fm.getMaxAdvance() / 2, h / 2 - 2);
    p.addPoint(fm.getMaxAdvance() / 2, h / 2 - 2);
    scrollUp = new WSButton(p, w, h);
    scrollUp.x = list.x;
    scrollUp.y = grid.y;
    scrollUp.setColors(bttnFgColor, bttnBgColor, scrnBdColor);
    p = new Polygon();
    p.addPoint(0, h / 2 - 2);
    p.addPoint(-fm.getMaxAdvance() / 2, -h / 2 + 2);
    p.addPoint(fm.getMaxAdvance() / 2, -h / 2 + 2);
    scrollDn = new WSButton(p, w, h);
    scrollDn.x = list.x;
    scrollDn.y = list.y + list.height + gap;
    scrollDn.setColors(bttnFgColor, bttnBgColor, scrnBdColor);

    // Create and position text buttons.

    g.setFont(bttnFont);
    fm = g.getFontMetrics();
    s = "New Game";
    w = fm.stringWidth(s) + fm.getMaxAdvance();
    h = 3 * fm.getHeight() / 2;
    newGame = new WSButton(s, bttnFont, w, h);
    x = 0;
    y = d.height - h;
    newGame.x = x;
    newGame.y = y;
    newGame.setColors(bttnFgColor, bttnBgColor, scrnBdColor);
    s = "Solve Game";
    x = x + w + gap;
    w = fm.stringWidth(s) + fm.getMaxAdvance();
    solveGame = new WSButton(s, bttnFont, w, h);
    solveGame.x = x;
    solveGame.y = y;
    solveGame.setColors(bttnFgColor, bttnBgColor, scrnBdColor);

    // Initialize game data.

    fileNum = 0;
    scroll = 0;
    timeText = "";
    statText = "";
    subjText = "";
    words = new Vector();
    grid.fill();
    endGame();
    gameState = INIT;
  }

  public Color getColorParm(String s) {

    int r, g, b;

    // Check if a pre-defined color is specified.

    if (s.equalsIgnoreCase("black"))
      return(Color.black);
    if (s.equalsIgnoreCase("blue"))
      return(Color.blue);
    if (s.equalsIgnoreCase("cyan"))
      return(Color.cyan);
    if (s.equalsIgnoreCase("darkGray"))
      return(Color.darkGray);
    if (s.equalsIgnoreCase("gray"))
      return(Color.gray);
    if (s.equalsIgnoreCase("green"))
      return(Color.green);
    if (s.equalsIgnoreCase("lightGray"))
      return(Color.lightGray);
    if (s.equalsIgnoreCase("magenta"))
      return(Color.magenta);
    if (s.equalsIgnoreCase("orange"))
      return(Color.orange);
    if (s.equalsIgnoreCase("pink"))
      return(Color.pink);
    if (s.equalsIgnoreCase("red"))
      return(Color.red);
    if (s.equalsIgnoreCase("white"))
      return(Color.white);
    if (s.equalsIgnoreCase("yellow"))
      return(Color.yellow);

    // If the color is specified in HTML format, build it from the red, green
    // and blue values.

    if (s.length() == 7 && s.charAt(0) == '#') {
      r = Integer.parseInt(s.substring(1,3),16);
      g = Integer.parseInt(s.substring(3,5),16);
      b = Integer.parseInt(s.substring(5,7),16);
      return(new Color(r, g, b));
    }

    // If we can't figure it out, default to black.

    return(Color.black);
  }

  public Font getFontParm(String s) {

    String t, fontName;
    StringTokenizer st;
    int n, fontStyle, fontSize;

    fontName = "";
    fontStyle = -1;
    fontSize = -1;

    // Parse font name.

    st = new StringTokenizer(s, ",");
    t = st.nextToken();
    if (t.equalsIgnoreCase("Courier"))
      fontName = "Courier";
    else if (t.equalsIgnoreCase("Dialog"))
      fontName = "Dialog";
    else if (t.equalsIgnoreCase("Helvetica"))
      fontName = "Helvetica";
    else if (t.equalsIgnoreCase("Symbol"))
      fontName = "Symbol";
    else if (t.equalsIgnoreCase("TimesRoman"))
      fontName = "TimesRoman";

    // Parse font style.

    t = st.nextToken();
    if (t.equalsIgnoreCase("plain"))
      fontStyle = Font.PLAIN;
    else if (t.equalsIgnoreCase("bold"))
      fontStyle = Font.BOLD;
    else if (t.equalsIgnoreCase("italic"))
      fontStyle = Font.ITALIC;
    else if (t.equalsIgnoreCase("boldItalic"))
      fontStyle = Font.BOLD + Font.ITALIC;

    // Parse font size.

    t = st.nextToken();
    if ((n = Integer.parseInt(t)) > 0)
      fontSize = n;

    // Return the specified font.

    if (fontName != "" && fontStyle >= 0 && fontSize >= 0)
      return(new Font(fontName, fontStyle, fontSize));

    // If we can't figure it out, return a null value.

    return (Font) null;
  }

  public void start() {

    if (loopThread == null) {
      loopThread = new Thread(this);
      loopThread.start();
    }
  }

  public void stop() {

    if (loopThread != null) {
      loopThread.stop();
      loopThread = null;
    }
  }

  public void run() {

    long threadTime;
    Date date;
    String mm, ss;

    // Lower this thread's priority and get the current time.

    Thread.currentThread().setPriority(Thread.MIN_PRIORITY);
    threadTime = System.currentTimeMillis();

    // This is the main loop.

    while (Thread.currentThread() == loopThread) {

      // Scroll word list.

      list.scroll += scroll;

      // Update game time.

      if (gameState == PLAY) {
        date = new Date(System.currentTimeMillis() - startTime);
        mm = (new Integer(date.getMinutes())).toString();
        ss = (new Integer(date.getSeconds())).toString();
        if (mm.length() < 2)
          mm = "0" + mm;
        if (ss.length() < 2)
          ss = "0" + ss;
        timeText = "Time: " + mm + ":" + ss;
        statText = "Found: " + count + "/" + words.size();
      }

      // Update the screen and set the timer for the next loop.

      repaint();
      try {
        threadTime += DELAY;
        Thread.sleep(Math.max(0, threadTime - System.currentTimeMillis()));
      }
      catch (InterruptedException e) {
        break;
      }
    }
  }

  public boolean mouseDown(Event e, int x, int y) {

    // Check buttons.

    if (newGame.inside(x, y))
      initGame();
    if (gameState == PLAY && solveGame.inside(x, y))
      solveGame();
    if (gameState != INIT && scrollUp.inside(x, y))
      scroll = -1;
    if (gameState != INIT && scrollDn.inside(x, y))
      scroll = 1;

    // Check grid.

    if (gameState == PLAY && grid.inside(x, y)) {
      grid.select = true;
      grid.startX = x;
      grid.startY = y;
      grid.endX = x;
      grid.endY = y;
    }

    return true;
  }

  public boolean mouseDrag(Event e, int x, int y) {

    if (gameState == PLAY && grid.select) {
      grid.endX = x;
      grid.endY = y;
    }

    return true;
  }

  public boolean mouseUp(Event e, int x, int y) {

    // Stop any scrolling.

    scroll = 0;

    // If a selection was being made, check it.

    if (gameState == PLAY && grid.select) {
      grid.select = false;
      if (grid.checkSelection())
        if (++count >= words.size()) {
          timeText += " Done!";
          statText = "Found: " + count + "/" + words.size();
          endGame();
        }
    }

    return true;
  }

  public void initGame() {

    setWords();
    grid.select = false;
    grid.fill();
    list.scroll = 0;
    count = 0;
    startTime = System.currentTimeMillis();
    timeText = "";
    statText = "";
    gameState = PLAY;
  }

  public void solveGame() {

    WSWord ws;
    int i;

    // Mark all words as found.

    for (i = 0; i < words.size(); i++) {
      ws = (WSWord) words.elementAt(i);
      ws.found = true;
    }
    count = words.size();
    timeText = "Cheated!";
    endGame();
  }

  public void endGame() {

    gameState = OVER;
  }

  public void setWords() {

    String s;
    URL url;
    InputStream in;
    DataInputStream ds;

    // Clear word list.

    words.removeAllElements();

    // Get next file URL.

    if (fileNum >= files.size())
      fileNum = 0;
    s = (String) files.elementAt(fileNum);
    url = (URL) null;
    try {
      url = new URL(getDocumentBase(), s);
    }
    catch (Exception e) {}
    fileNum++;

    // Open it up and read list of words.

    subjText = "";
    try {
      in = url.openStream();
      ds = new DataInputStream(in);
      while((s = ds.readLine()) != null)
        if (s.startsWith("#"))
          subjText = "'" + s.substring(1) + "'";
        else
          words.addElement(new WSWord(s));
    }
    catch (IOException e) {}
  }

  public void paint(Graphics g) {

    update(g);
  }

  public void update(Graphics g) {

    Dimension d = size();
    FontMetrics fm;
    String s1, s2;
    int x, y;
    int w, h;

    // Create the offscreen graphics context, if no good one exists.

    if (offGraphics == null || d.width != offDimension.width || d.height != offDimension.height) {
      offDimension = new Dimension(d.width, d.height);
      offImage = createImage(d.width, d.height);
      offGraphics = offImage.getGraphics();
    }

    // Color background.

    offGraphics.setColor(scrnBgColor);
    offGraphics.fillRect(0, 0, d.width, d.height);

    // Draw elements.

    grid.draw(offGraphics);
    list.draw(offGraphics);
    scrollUp.draw(offGraphics);
    scrollDn.draw(offGraphics);
    newGame.draw(offGraphics);
    solveGame.draw(offGraphics);

    // Display title, messages, etc. as appropriate.

    offGraphics.setColor(scrnFgColor);
    offGraphics.setFont(scrnFont);
    fm = offGraphics.getFontMetrics();

    if (gameState == INIT) {
      x = (grid.x + grid.width) / 2;
      y = (grid.y + grid.height) / 2;
      s1 = "Word Search";
      s2 = "Copyright 1999 by Mike Hall";
      w = Math.max(fm.stringWidth(s1), fm.stringWidth(s2)) + 2 * fm.getMaxAdvance();
      h = 5 * fm.getHeight();
      offGraphics.setColor(gridFgColor);
      offGraphics.fillRect(x - w / 2, y - h / 2, w, h);
      offGraphics.setColor(gridBgColor);
      offGraphics.drawRect(x - w / 2 + 1, y - h / 2 + 1, w - 3, h - 3);
      offGraphics.drawString(s1, x - fm.stringWidth(s1) / 2, y - h / 2 + 2 * fm.getHeight());
      offGraphics.drawString(s2, x - fm.stringWidth(s2) / 2, y + h / 2 - fm.getHeight() - fm.getDescent());
    }

    x = (grid.x + grid.width - fm.stringWidth(subjText)) / 2;
    y = grid.y + grid.height + fm.getHeight();
    offGraphics.drawString(subjText, x, y);

    s1 = timeText;
    x = Math.min(d.width - fm.stringWidth(s1), list.x);
    offGraphics.drawString(s1, x, y);

    s1 = statText;
    x = Math.min(d.width - fm.stringWidth(s1), list.x);
    y += fm.getHeight();
    offGraphics.drawString(s1, x, y);

    // Copy the off-screen buffer to the screen.

    g.drawImage(offImage, 0, 0, this);
  }
}
