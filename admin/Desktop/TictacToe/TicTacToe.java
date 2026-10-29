import javax.swing.*;
import java.awt.*;
import java.awt.event.*;

public class TicTacToe extends JFrame implements ActionListener {
    private JButton[] buttons = new JButton[9];
    private JButton resetBtn;
    private char currentPlayer = 'X';
    private boolean gameOver = false;
    private int scoreX = 0;
    private int scoreO = 0;
    private JLabel scoreLabel;
    private ImageIcon iconX;
    private ImageIcon iconO;
    private int[] winningCombo = null;
    private char[] board = new char[9];

    public TicTacToe() {
        setTitle("Tic-Tac-Toe: Best of Five");
        setSize(360, 420);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLayout(new BorderLayout());

        iconX = resizeIcon(new ImageIcon("src/Xpic.jpg"), 100, 100);
        iconO = resizeIcon(new ImageIcon("src/Opic.png"), 100, 100);
        JPanel boardPanel = new JPanel(new GridLayout(3, 3));

        for (int i = 0; i < 9; i++) {
            buttons[i] = new JButton("");
            buttons[i].setFocusPainted(false);
            buttons[i].addActionListener(this);
            boardPanel.add(buttons[i]);
            board[i] = ' ';
        }
        JPanel controls = new JPanel(new GridLayout(1, 2));
        resetBtn = new JButton("Reset");
        resetBtn.addActionListener(e -> resetMatch());
        scoreLabel = new JLabel("Score X: 0 | O: 0", SwingConstants.CENTER);

        controls.add(resetBtn);
        controls.add(scoreLabel);

        add(boardPanel, BorderLayout.CENTER);
        add(controls, BorderLayout.SOUTH);

        setVisible(true);
    }
    @Override
    public void actionPerformed(ActionEvent e) {
        if (gameOver) return;
        JButton clicked = (JButton) e.getSource();
        int index = getButtonIndex(clicked);
        if (board[index] != ' ') return;
        placeMove(index, currentPlayer);
        if (checkWin()) return;
        if (isBoardFull()) {
            JOptionPane.showMessageDialog(this, "It's a draw!");
            nextRound();
            return;
        }
        currentPlayer = (currentPlayer == 'X') ? 'O' : 'X';
    }
    private int getButtonIndex(JButton btn) {
        for (int i = 0; i < 9; i++)
            if (buttons[i] == btn) return i;
        return -1;
    }
    private void placeMove(int index, char player) {
        board[index] = player;
        buttons[index].setIcon(player == 'X'
                ? resizeIcon(iconX, buttons[index].getWidth(), buttons[index].getHeight())
                : resizeIcon(iconO, buttons[index].getWidth(), buttons[index].getHeight()));
    }
    private boolean checkWin() {
        int[][] winPatterns = {
                {0, 1, 2}, {3, 4, 5}, {6, 7, 8},
                {0, 3, 6}, {1, 4, 7}, {2, 5, 8},
                {0, 4, 8}, {2, 4, 6}
        };
        for (int[] p : winPatterns) {
            if (board[p[0]] != ' ' &&
                    board[p[0]] == board[p[1]] &&
                    board[p[1]] == board[p[2]]) {
                winningCombo = p;
                highlightWinningCombo();
                gameOver = true;

                if (currentPlayer == 'X') scoreX++;
                else scoreO++;

                updateScore();

                JOptionPane.showMessageDialog(this,
                        "Player " + currentPlayer + " wins this round!");

                if (!checkBestOfFive())
                    nextRound();
                return true;
            }
        }
        return false;
    }
    private void highlightWinningCombo() {
        for (int i : winningCombo)
            buttons[i].setBackground(Color.YELLOW);
    }
    private boolean isBoardFull() {
        for (char c : board)
            if (c == ' ') return false;
        return true;
    }
    private void nextRound() {
        Timer t = new Timer(1000, e -> resetBoard());
        t.setRepeats(false);
        t.start();
    }
    private void resetBoard() {
        for (int i = 0; i < 9; i++) {
            buttons[i].setIcon(null);
            buttons[i].setBackground(null);
            board[i] = ' ';
        }
        currentPlayer = 'X';
        winningCombo = null;
        gameOver = false;
    }
    private void resetMatch() {
        resetBoard();
        scoreX = 0;
        scoreO = 0;
        updateScore();
    }
    private void updateScore() {
        scoreLabel.setText("Score X: " + scoreX + " | O: " + scoreO);
    }
    private boolean checkBestOfFive() {
        if (scoreX == 3 || scoreO == 3) {
            JOptionPane.showMessageDialog(this,
                    (scoreX == 3 ? "Player X" : "Player O") +
                            " wins the match (Best of Five)!");
            resetMatch();
            return true;
        }
        return false;
    }
    private ImageIcon resizeIcon(ImageIcon icon, int width, int height) {
        Image img = icon.getImage();
        return new ImageIcon(img.getScaledInstance(width, height, Image.SCALE_SMOOTH));
    }
    public static void main(String[] args) {
        new TicTacToe();
    }
}
