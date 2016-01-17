/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package BSU.CS690.IntroNet;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.Socket;
import java.util.Properties;

/**
 *
 * @author hussam
 */
public class Client extends Thread {
    private Socket socket;
    String user;
    int id;
    String ip; // ip adress of the client
    boolean Logedin=false;// is the client logedin
    int lastLogin; // the last login of the client
    
    public Client(Socket socket, int id) {
            this.socket = socket;
            this.id = id;
        }
    
    public void run() {
            
            
            if(this.socket.isConnected())
            try {
                
               BufferedReader in = new BufferedReader(
                        new InputStreamReader(socket.getInputStream()));
                String s;
                s = in.readLine();
                String r = java.net.URLDecoder.decode(s.replace("HTTP/1.1", ""));
                if(r.trim().equals("GET /favicon.ico"))
                    return;
                Properties props = new Properties();
                while((s=in.readLine()) != null && !s.equals("")) {
                    //System.out.println("in ="+s); // for testing
                    String[] q = s.split(": ");
                    props.put(q[0], q[1]);
                }
                
                Request request = new Request();
                request.type = r.split(" ")[0];
                request.path = r.split(" ")[1];
                request.ip = socket.getRemoteSocketAddress().toString();
                request.data = new Properties();
                
                // get the post data if the type of request is POST
                if(request.type.equals("POST"))
                {
                    StringBuilder data = new StringBuilder();
                    for(int i=0; i< Integer.parseInt(props.get("Content-Length").toString());i++)
                        data.append((char) in.read());
                    for(String d: data.toString().split("&"))
                    {
                        //System.out.println(d);
                      //  request.data.put(d, d);
                        request.data.put(d.split("=")[0], d.split("=").length>1?d.split("=")[1]:"");
                    }
                    //request.data = data.toString();
                }
                else if(request.type.equals("GET"))
                {
                    Webpage.sendWebPage("../Interface/index.html", socket);
                }
                
            }
            catch (Exception e) {
                //disconnect(e.getMessage());
                e.printStackTrace();
                System.out.println(e.getMessage());
            }
            finally {
                try {
                    //user.server = null;
                    socket.close();
                    System.out.println("socket "+user+" was closed");
                    //numberOfClient--;
                    //players.remove(this);
                } catch (IOException e) {
                    System.out.println("Couldn't close a socket, what's going on?");
                }
                System.out.println("Connection with client# " + user + " closed");
            }
    }
    

}
