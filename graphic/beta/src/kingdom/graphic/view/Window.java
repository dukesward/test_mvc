package kingdom.graphic.view;

import java.awt.Canvas;

import javax.swing.JFrame;

public class Window extends JFrame {
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	private Canvas canvas;
	//JFrame settings
	private int width;
	private int height;
	private String name;
	
	public Window(String name, int width, int height) {
		this.width = width;
		this.height = height;
		this.name = name;
	}
	
	public Window(String name) {
		this(name, 640, 480);
	}
	
	public void init() {
		this.setName(this.name);
		this.getContentPane().add(this.canvas);
		this.setSize(this.width, this.height);
		this.setLocationRelativeTo(null);
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		this.setVisible(true);
		this.setResizable(false);
	}
}
