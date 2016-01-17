/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package BSU.CS690.IntroNet;

import java.io.IOException;
import java.net.ServerSocket;
import java.util.HashMap;
import java.util.Map;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hussam
 */
public class Server {

    // List of Clients

    Map<Integer, Client> clients = new HashMap<Integer, Client>();
    ServerSocket listener; // Server listener
    int numberOfClient;
    String loginPage = ""; // the path of the login page
    static final int PORT = 8080;

    public Server(int port) throws IOException {
        listener = new ServerSocket(port);
        System.out.println("Server is running on port:" + port);
        this.run();
    }

    public void run() {
        try {
            while (true) {

                ++numberOfClient;// = ++numberOfClient>Integer.MAX_VALUE?1:numberOfClient; // for testing

                Client p = new Client(listener.accept(), numberOfClient); // replace numberOfClient with userID
                clients.put(numberOfClient, p);
                p.start();

            }
        } catch (IOException ex) {
            Logger.getLogger(Server.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        try {
            // TODO code application logic here
            new Server(PORT);
        } catch (IOException ex) {
            System.out.println("Server cannot run on port:" + PORT);
            Logger.getLogger(Server.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

}
