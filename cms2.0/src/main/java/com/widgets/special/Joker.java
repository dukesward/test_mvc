package com.widgets.special;

import java.lang.annotation.Annotation;
import java.lang.reflect.Method;
import java.util.HashMap;
import java.util.Map;

public class Joker<T> {
	
	private Map<String, T> queue;
	private Map<String, Object> callbacks;
	private Object manager;
	
	public Joker(Object manager) {
		this.queue = new HashMap<String, T>();
		this.callbacks = new HashMap<String, Object>();
		this.manager = manager;
	}
	
	public T getElement(String key) {
		return this.queue.get(key);
	}
	
	public void registerElement(String key, T element) {
		this.queue.put(key, element);
	}
	
	public void registerElement(String key, T element, String callback) {
		this.registerElement(key, element);
		this.callbacks.put(key, callback);
	}
	
	public void trigger() {
		System.out.println("trigger joker for: " + this.manager.getClass());
		//this.triggerManager();
		this.triggerElements();
	}
	
	public void triggerElement(Object element) {
		Class c = element.getClass();
		System.out.println("test trigger element: " + element.getClass());
		for(Method method : c.getDeclaredMethods()) {
			if(method.isAnnotationPresent(Invoke.class)) {
				Annotation a = method.getAnnotation(Invoke.class);
				Invoke invoke = (Invoke)a;
				String type = invoke.type();
				String trigger = invoke.trigger();
				if(trigger.equals("default")) {
					try {
						method.invoke(element, (Object[])null);
					}catch(Exception e) {
						System.out.println("the method: " + method + " of " + c + " cannot be invoked");
					}
				}else {
					if(type.equals("")) {
						
					}else {
						for(Map.Entry<String, T> entry:this.queue.entrySet()) {
							Class _c = entry.getValue().getClass();
							
						}
					}
				}
			}
		}
	}
	
	public void triggerElements() {
		for(Map.Entry<String, T> entry:this.queue.entrySet()) {
			this.triggerElement(entry.getValue());
		}
	}
}