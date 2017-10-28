package kingdom.graphic.bootstrap;

import kingdom.graphic.core.SystemManager;
import kingdom.graphic.view.Window;

public class Bootstrap {
	
	public static void main(String[] args) {
		SystemManager system = new SystemManager();
		Window window = new Window("test");
		system.printSystem();
	}
}
